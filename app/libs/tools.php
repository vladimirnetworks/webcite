<?php
function fiximgurl($imgurl, $base)
{
    $imgurl_parsed = parse_url($imgurl);
    $base_parsed = parse_url($base);

    $scheme = null;
    if (isset($imgurl_parsed['scheme'])) {
        $scheme = strtolower($imgurl_parsed['scheme']);
    }


    if ($scheme == 'https' || $scheme == 'http') {
        $finalimgurl = $imgurl;
    } else {

        $imgurl = trim($imgurl);

        if (preg_match('!^\/!', $imgurl)) {
            $finalimgurl = $base_parsed['scheme'] . "://" . $base_parsed['host'] . '/' . ltrim(trim($imgurl), "/");
        } else {
            $finalimgurl = $base_parsed['scheme'] . "://" . $base_parsed['host'] . '/' . ltrim(preg_replace('!(?<=\/)[^\/]+$!', "", $base_parsed['path']), "/") . $imgurl;
        }
    }
    return $finalimgurl;
}


function oneSpace($input)
{
    return trim(preg_replace('!\s+!', ' ', $input));
}


function onlyFarsi($input)
{
    return oneSpace(preg_replace('/[^آابپتثجچ‌حخدذرز‌ژس‌شصضطظعغفقکگلمنوهی]/', ' ', arabic_to_farsi($input)));
}

function spaceToDash($input)
{
    return str_replace(" ", "-", oneSpace($input));
}


function arabic_to_farsi($inp)
{

    $f[] = 'ي';
    $r[] = 'ی';

    $f[] = 'ك';
    $r[] = 'ک';


    return str_replace($f, $r, $inp);
}

function persiannumber($i)
{

    $f = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
    $r = ["۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "۰"];
    return str_replace($f, $r, $i);
}


function englishnumber($i)
{


    $f = ["۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "۰"];
    $r = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
    return str_replace($f, $r, $i);
}

function just_text($inp)
{
    $x = trim(html_entity_decode(strip_tags($inp)));
    $x = trim(str_replace(urldecode("%C2%A0"), "", $x));
    if ($x != '') {
        return  $x;
    } else {
        return null;
    }
}

function just_number($inp)
{
    return trim(preg_replace('/[^\d]/', '', $inp));
}


function fuck($text)
{
    $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;
    return  preg_replace($regex, '$1', $text);
}