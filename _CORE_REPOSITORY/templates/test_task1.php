<?php

class Test1Solutions extends Controller
{
    function solution($aString, $bString)
    {
        if (!strlen($aString) || !strlen($bString)) {
            return false;
        }
        //make char collections
        $aCharCollection = preg_split('//u', $aString, -1, PREG_SPLIT_NO_EMPTY);
        $bCharCollection = preg_split('//u', $bString, -1, PREG_SPLIT_NO_EMPTY);
        //count
        $aCharCount = count($aCharCollection);
        $bCharCount = count($bCharCollection);
        //check utmost variants
        if ($aCharCollection === $bCharCollection) {
            return 'РАВЕНСТВО';
        }

        if (abs($aCharCount - $bCharCount) > 1) {
            return 'НЕВОЗМОЖНО';
        }

        if ($aCharCount < $bCharCount) { //insert required
            $this->insertAssertion($aCharCollection, $bCharCollection);
        } else if ($aCharCount > $bCharCount) { //delete required
            $this->deleteAssertion($aCharCollection, $bCharCollection);
        } else { //switch required
            $this->switchAssertion($aCharCollection, $bCharCollection);
        }
    }


    function insertAssertion(array $input, array $target)
    {
        for ($i = 0; $i <= count($input); $i++) {
            foreach (range(chr(0xE0), chr(0xFF)) as $ruChar) {
                $compiledChars = array();
                $ruChar = iconv('CP1251', 'UTF-8', $ruChar);
                $compiledChars[$i] = $ruChar;
                foreach ($input as $k => $val) {
                    if ($k >= $i) {
                        $compiledChars[$k + 1] = $val;
                    } else {
                        $compiledChars[$k] = $val;
                    }
                }

                ksort($compiledChars);

                if ($compiledChars === $target) {
                    return 'ВСТАВИТЬ ' . $ruChar;
                }
            }
        }
        return false;
    }


    function deleteAssertion(array $input, array $target)
    {
        for ($i = 0; $i < count($input); $i++) {
            $compiledChars = $input;
            $delChar = $compiledChars[$i];
            unset($compiledChars[$i]);
            if (implode($compiledChars) === implode($target)) {
                return 'УДАЛИТЬ ' . $delChar;
            }
        }
        return false;
    }


    function switchAssertion(array $input, array $target)
    {
        //count chars
        $inpCharsCount = array_count_values($input);
        $targetCharsCount = array_count_values($target);
        ksort($inpCharsCount);
        ksort($targetCharsCount);
        if ($inpCharsCount === $targetCharsCount) {
            //begin switches
            for ($i = 0; $i < count($input) - 1; $i++) {
                $compiledChars = $input;
                $t = $compiledChars[$i];
                $compiledChars[$i] = $compiledChars[$i + 1];
                $compiledChars[$i + 1] = $t;
                if (implode($compiledChars) === implode($target)) {
                    return "ПОМЕНЯТЬ " . $t . " " . $compiledChars[$i];
                }
            }
        }
        return false;
    }
}


