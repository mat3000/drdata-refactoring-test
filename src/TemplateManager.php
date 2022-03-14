<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote)
        {
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
            $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

            $containsSummaryHtml     = strpos($text, '[quote:summary_html]') !== false;
            $containsSummary         = strpos($text, '[quote:summary]') !== false;
            $containsDestinationName = strpos($text, '[quote:destination_name]') !== false;
            $containsDestinationLink = strpos($text, '[quote:destination_link]') !== false;

            if ($containsSummaryHtml) {
                $text = str_replace(
                    '[quote:summary_html]',
                    Quote::renderHtml($_quoteFromRepository),
                    $text
                );
            }

            if ($containsSummary) {
                $text = str_replace(
                    '[quote:summary]',
                    Quote::renderText($_quoteFromRepository),
                    $text
                );
            }

            if($containsDestinationName) {
                $text = str_replace(
                    '[quote:destination_name]',
                    $destinationOfQuote->countryName,
                    $text
                );
            }
        
            if($containsDestinationLink) {
                $url = $destinationOfQuote ? "{$usefulObject->url}/{$destinationOfQuote->countryName}/quote/{$_quoteFromRepository->id}" : '';
                $text = str_replace('[quote:destination_link]', $url, $text);
            }
        }

        /*
         * USER
         * [user:*]
         */
        $_user  = (isset($data['user']) and ($data['user'] instanceof User)) ? $data['user'] : $APPLICATION_CONTEXT->getCurrentUser();
        
        if($_user) {

            $containsFirstName = strpos($text, '[user:first_name]') !== false;

            if( $containsFirstName ) {
                $text = str_replace(
                    '[user:first_name]',
                    ucfirst(mb_strtolower($_user->firstname)),
                    $text
                );
            }
        }

        return $text;
    }
}
