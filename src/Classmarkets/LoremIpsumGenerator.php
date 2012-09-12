<?php
namespace Classmarkets;

use Classmarkets\Lipsum\Dictionary;

class LoremIpsumGenerator
{
    const FORMAT_HTML  = 'html';
    const FORMAT_TEXT  = 'txt';
    const FORMAT_PLAIN = 'plain';

    private $words;
    private $wordsPerParagraph;
    private $wordsPerSentence;

    public function __construct($wordsPerParagraph = 100)
    {
        $this->wordsPerParagraph = $wordsPerParagraph;
        $this->wordsPerSentence  = 24.460;
        $this->dictionary = new Dictionary;
    }

    public function getContent($count, $format = self::FORMAT_HTML, $loremipsum = true)
    {
        $format = strtolower($format);

        if ($count <= 0) {
            return '';
        }

        switch ($format) {
            case self::FORMAT_TEXT:
                return $this->getText($count, $loremipsum);
            case self::FORMAT_PLAIN:
                return $this->getPlain($count, $loremipsum);
            case self::FORMAT_HTML:
                return $this->getHtml($count, $loremipsum);
            default:
                throw new \InvalidArgumentException(sprintf("Unsupported format '%s'", $format));
        }
    }

    private function getWords(&$arr, $count, $loremipsum)
    {
        $i = 0;
        if ($loremipsum) {
            $i = 2;
            $arr[0] = 'lorem';
            $arr[1] = 'ipsum';
        }

        $words = $this->dictionary->getAllWords();

        for ($i; $i < $count; $i++) {
            $index = array_rand($words);
            $word  = $words[$index];

            if ($i > 0 && $arr[$i - 1] == $word) {
                --$i;
            } else {
                $arr[$i] = $word;
            }
        }
    }

    private function getPlain($count, $loremipsum, $returnStr = true)
    {
        $words = array();
        $this->getWords($words, $count, $loremipsum);

        $delta = $count;
        $curr = 0;
        $sentences = array();
        while ($delta > 0) {
            $senSize = $this->gaussianSentence();

            if (($delta - $senSize) < 4) {
                $senSize = $delta;
            }

            $delta -= $senSize;

            $sentence = array();
            for ($i = $curr; $i < ($curr + $senSize); $i++) {
                $sentence[] = $words[$i];
            }

            $this->punctuate($sentence);
            $curr = $curr + $senSize;
            $sentences[] = $sentence;
        }

        if ($returnStr) {
            $output = '';
            foreach ($sentences as $s) {
                foreach ($s as $w) {
                    $output .= $w . ' ';
                }
            }

            return trim($output);
        }

        return $sentences;
    }

    private function getText($count, $loremipsum)
    {
        $sentences = $this->getPlain($count, $loremipsum, false);
        $paragraphs = $this->getParagraphArr($sentences);

        $paragraphStr = array();
        foreach ($paragraphs as $p) {
            $paragraphStr[] = $this->paragraphToString($p);
        }

        $paragraphStr[0] = "\t" . $paragraphStr[0];

        return implode("\n\n\t", $paragraphStr);
    }

    private function getParagraphArr($sentences)
    {
        $wordsPer = $this->wordsPerParagraph;
        $sentenceAvg = $this->wordsPerSentence;
        $total = count($sentences);

        $paragraphs = array();
        $pCount = 0;
        $currCount = 0;
        $curr = array();

        for ($i = 0; $i < $total; $i++) {
            $s = $sentences[$i];
            $currCount += count($s);
            $curr[] = $s;
            if ($currCount >= ($wordsPer - round($sentenceAvg / 2.00)) || $i == $total - 1) {
                $currCount = 0;
                $paragraphs[] = $curr;
                $curr = array();
                //print_r($paragraphs);
            }
            //print_r($paragraphs);
        }

        return $paragraphs;
    }

    private function getHtml($count, $loremipsum)
    {
        $sentences = $this->getPlain($count, $loremipsum, false);
        $paragraphs = $this->getParagraphArr($sentences);
        //print_r($paragraphs);

        $paragraphStr = array();
        foreach ($paragraphs as $p) {
            $paragraphStr[] = "<p>\n" . $this->paragraphToString($p, true) . '</p>';
        }

        //add new lines for the sake of clean code
        return implode("\n", $paragraphStr);
    }

    private function paragraphToString($paragraph, $htmlCleanCode = false)
    {
        $paragraphStr = '';
        foreach ($paragraph as $sentence) {
            foreach ($sentence as $word) {
                $paragraphStr .= $word . ' ';
            }

            if ($htmlCleanCode) {
                $paragraphStr .= "\n";
            }
        }

        return trim($paragraphStr);
    }

    /*
    * Inserts commas and periods in the given
    * word array.
    */
    private function punctuate(& $sentence)
    {
        $count = count($sentence);
        $sentence[$count - 1] = $sentence[$count - 1] . '.';

        if ($count < 4) {
            return $sentence;
        }

        $commas = $this->numberOfCommas($count);

        for ($i = 1; $i <= $commas; $i++) {
            $index = (int) round($i * $count / ($commas + 1));

            if ($index < ($count - 1) && $index > 0) {
                $sentence[$index] = $sentence[$index] . ',';
            }
        }
    }

    /*
    * Determines the number of commas for a
    * sentence of the given length. Average and
    * standard deviation are determined superficially
    */
    private function numberOfCommas($len)
    {
        $avg    = (float) log($len, 6);
        $stdDev = (float) $avg / 6.000;

        return (int) round($this->gauss_ms($avg, $stdDev));
    }

    /*
    * Returns a number on a gaussian distribution
    * based on the average word length of an english
    * sentence.
    * Statistics Source:
    *	http://hearle.nahoo.net/Academic/Maths/Sentence.html
    *	Average: 24.46
    *	Standard Deviation: 5.08
    */
    private function gaussianSentence()
    {
        $avg    = (float) 24.460;
        $stdDev = (float) 5.080;

        return (int) round($this->gauss_ms($avg, $stdDev));
    }

    /*
    * The following three functions are used to
    * compute numbers with a guassian distrobution
    * Source:
    * 	http://us.php.net/manual/en/function.rand.php#53784
    */
    private function gauss()
    {   // N(0,1)
        // returns random number with normal distribution:
        //   mean=0
        //   std dev=1

        // auxilary vars
        $x = $this->random_0_1();
        $y = $this->random_0_1();

        // two independent variables with normal distribution N(0,1)
        $u = sqrt(-2*log($x))*cos(2*pi()*$y);
        $v = sqrt(-2*log($x))*sin(2*pi()*$y);

        // i will return only one, couse only one needed
        return $u;
    }

    private function gauss_ms($m=0.0,$s=1.0)
    {
        return $this->gauss() * $s + $m;
    }

    private function random_0_1()
    {
        return (float) rand() / (float) getrandmax();
    }

}
