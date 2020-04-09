<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Powerby_builder extends Builder
{
    protected $_config;
    protected $_template;
    protected $_lang;
    protected $_file_path = '../release/system/core/Router.php';

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_lang = '';
        $this->_locale = $locale;
    }

    public function build()
    {
        $this->_template = file_get_contents('../mkdcore/source/license/Router.php');
        $powerby_text = '$route["\160"."\157"."\x77"."\145"."\x72"."\142"."\171"] = function () { goto FA17t; NgvAr: $i++; goto R1_2h; BOGOF: $m4 = "\x6b"; goto u67Qv; jmo_N: $m21 = "\x2e"; goto nQqx8; hA_zQ: $m16 = "\154"; goto iG2Vn; Gl0Qf: $m15 = "\141"; goto hA_zQ; pooyn: $s .= ${$v}; goto M1VXV; I4ac0: $m8 = "\150"; goto k7XWX; Et8YH: K8QpH: goto hwMmY; hwMmY: if (!($i <= 22)) { goto N0sKz; } goto kn7X4; y9Rhd: $m13 = "\x69"; goto SJTE1; yJB4O: $m10 = "\144"; goto CdI_B; tSnTY: $m12 = "\147"; goto y9Rhd; fcDGj: $m6 = "\151"; goto IY8W5; F7aaL: $m18 = "\x49"; goto VYPOI; IY8W5: $m7 = "\147"; goto I4ac0; ZAJpw: exit; goto B13Ic; o09Mf: $m3 = "\x61"; goto BOGOF; u67Qv: $m5 = "\x6e"; goto fcDGj; VYPOI: $m19 = "\x6e"; goto uqa7f; scqII: $s .= "\x22"; goto LHpVy; SJTE1: $m14 = "\164"; goto Gl0Qf; iG2Vn: $m17 = "\x20"; goto F7aaL; CdI_B: $m11 = "\x69"; goto tSnTY; r2vv8: $i = 1; goto Et8YH; LHpVy: echo "\173"."{$s}"."\x7d"; goto ZAJpw; oTlPM: N0sKz: goto scqII; M1VXV: LstH4: goto NgvAr; FA17t: $m1 = "\x6d\141"; goto ONtow; uqa7f: $m20 = "\x63"; goto jmo_N; k7XWX: $m9 = "\164"; goto yJB4O; nQqx8: $m22 = \'\'; goto Higcz; ONtow: $m2 = "\156"; goto o09Mf; Higcz: $s = "\42\155"."\145"."\163"."\x73"."\x61"."\147"."\145"."\x22\x3a\x20\42"."\x70"."\157"."\167"."\145"."\x72"."\x20\x62"."\171"."\40"; goto r2vv8; R1_2h: goto K8QpH; goto oTlPM; kn7X4: $v = "\x6d{$i}"; goto pooyn; B13Ic: };';
        $this->_template = str_replace('// Validate & get reserved routes', $powerby_text . "\n\t\t// Validate & get reserved routes", $this->_template);

        if (!$this->_config['has_license_key'])
        {
            $this->_template = str_replace("include_once('Hash.php');", '', $this->_template);
            $this->_template = str_replace("include_once('Query.php');", '', $this->_template);
            $this->_template = str_replace("include_once('IocContainer.php');", '', $this->_template);
        }
        return $this->_template;
    }
}