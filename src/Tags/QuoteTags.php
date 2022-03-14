<?php

class QuoteTags
{
    public $quote;
    public $quoteFromRepository;
    public $usefulObject;
    public $destinationOfQuote;

    public function __construct($quote)
    {
        $this->quote = (isset($quote) and $quote instanceof Quote) ? $quote : null;
        $this->quoteFromRepository = $this->quote ? QuoteRepository::getInstance()->getById($quote->id) : null;
        $this->usefulObject = $this->quote ?SiteRepository::getInstance()->getById($quote->siteId) : null;
        $this->destinationOfQuote = $this->quote ?DestinationRepository::getInstance()->getById($quote->destinationId) : null;
    }


    public function destinationName(string $text)
    {   
        $tag = '[quote:destination_name]';

        if($this->quote && strpos($text, $tag) !== false) {
            return str_replace(
                $tag,
                $this->destinationOfQuote->countryName,
                $text
            );
        }

        return $text;
    }

    public function summary(string $text)
    {
        $tag = '[quote:summary]';

        if($this->quote && strpos($text, $tag) !== false) {
            return str_replace(
                '[quote:summary]',
                Quote::renderText($this->quoteFromRepository),
                $text
            );
        }

        return $text;
    }

    public function summaryHtml(string $text)
    {
        $tag = '[quote:summary_html]';

        if($this->quote && strpos($text, $tag) !== false) {
            return str_replace(
                '[quote:summary_html]',
                Quote::renderHtml($this->quoteFromRepository),
                $text
            );
        }

        return $text;
    }

    public function destinationLink(string $text)
    {
        $tag = '[quote:destination_link]';

        if($this->quote && strpos($text, $tag) !== false) {

            $url = $this->destinationOfQuote ? "{$this->usefulObject->url}/{$this->destinationOfQuote->countryName}/quote/{$this->quoteFromRepository->id}" : '';

            return str_replace(
                '[quote:destination_link]',
                $url,
                $text
            );
        }

        return $text;
    }

}