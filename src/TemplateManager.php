<?php
require_once __DIR__ . '/Tags/QuoteTags.php';
require_once __DIR__ . '/Tags/UserTags.php';

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

        /*
         * QUOTE
         */

        $quoteTags = new QuoteTags($data['quote']);

        $text = $quoteTags->summaryHtml($text);

        $text = $quoteTags->summary($text);

        $text = $quoteTags->destinationName($text);

        $text = $quoteTags->destinationLink($text);

        /*
         * USER
         * [user:*]
         */
        $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();

        $userTags = new UserTags($_user);
        $text = $userTags->firstName($text, $_user);

        return $text;
    }
}
