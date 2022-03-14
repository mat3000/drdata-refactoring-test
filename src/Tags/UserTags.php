<?php

class UserTags
{
    public $user;
    public $quoteFromRepository;
    public $usefulObject;
    public $destinationOfQuote;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function firstName(string $text, User $user)
    {   
        $tag = '[user:first_name]';

        if(strpos($text, $tag) !== false) {
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

            return str_replace(
                $tag,
                ucfirst(mb_strtolower($user->firstname)),
                $text
            );
        }

        return $text;
    }

}