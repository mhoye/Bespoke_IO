<?php
    require_once dirname(__FILE__).'/productHistory.class.php';

    /**
     * Dates of release for certain Firefox Mobile versions
     */
    class mobileHistory extends productHistory {
        // Major releases
        var $major_releases = array(
                '1.0' => '2010-01-28',
                '1.1' => '2010-07-01',
            );

        // Security and stability releases
        var $stability_releases = array(
                '1.0.1'    => '2010-04-13',
            );

        // Development releases - betas and release candidates only
        var $development_releases = array(
                '1.1b1' => '2010-04-28',
                '1.1rc1' => '2010-06-16',
                '4.0b1' => '2010-10-06',
                '4.0b2' => '2010-11-04',
                '4.0b3' => '2010-12-22',
            );
    }

?>
