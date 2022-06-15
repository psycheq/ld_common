<?php

class Passport
{
    public function encrypt($txt, $key, $isRand = true)
    {
        if ($isRand) {
            srand((double)microtime() * 1000000);
            $encrypt_key = md5(rand(0, 32000));
        } else {
            $encrypt_key = '123456789';
        }
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $encrypt_key[$ctr] . ($txt[$i] ^ $encrypt_key[$ctr++]);
        }
        $code = base64_encode($this->key($tmp, $key));
        $code = str_replace('+', '-', $code);
        $code = str_replace('/', '_', $code);
        $code = str_replace('=', '~', $code);
        return $code;
    }

//解密函数
    public function decrypt($txt, $key)
    {
        $txt = str_replace('-', '+', $txt);
        $txt = str_replace('_', '/', $txt);
        $txt = str_replace('~', '=', $txt);
        $txt = $this->key(base64_decode($txt), $key);
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = $txt[$i];
            $tmp .= $txt[++$i] ^ $md5;
        }
        return $tmp;
    }

    private function key($txt, $encrypt_key)
    {
        $encrypt_key = md5($encrypt_key);
        $ctr         = 0;
        $tmp         = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }
}