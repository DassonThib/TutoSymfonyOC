<?php
// src/BVA/PlatformBundle/Antispam/BVAAntispam.php

namespace BVA\PlatformBundle\Antispam;

class BVAAntispam
{
    private $mailer;
    private $locale;
    private $minlength;


    public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
        $this->mailer = $mailer;
        $this->locale = $locale;
        $this->minLength = (int) $minLength;
    }

    /**
     * Vérifie si le texte est un spam ou non
     * 
     * @param string $text
     * @return bool
     */
    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }
}
?>