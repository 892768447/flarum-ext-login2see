<?php

namespace Irony\Login\See\Listeners;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Post\CommentPost;
use Illuminate\Contracts\Events\Dispatcher;
use Symfony\Component\Translation\TranslatorInterface;

class Login2See
{
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Serializing::class, [$this, 'serializing']);
    }

    public function handle(Serializing $event)
    {
        if ($event->isSerializer(PostSerializer::class) && $event->model instanceof CommentPost) {

            $newHTML = $event->attributes['contentHtml'];
            if (strpos($newHTML, '<login2see>') === false)
                return;

            $isStartPost = $event->model['discussion']->first_post_id == $event->model->id;
            if (!$isStartPost) {
                $newHTML = preg_replace('/<login2see>(.*?)<\/login2see>/is', '<div>$1</div>', $newHTML);
                $event->attributes['contentHtml'] = $newHTML;
                return;
            }

            $logined = !$event->actor->isGuest();

            if ($logined)
                $newHTML = preg_replace('/<login2see>(.*?)<\/login2see>/is', '<div class="login2see">$1</div>', $newHTML);
            else
                $newHTML = preg_replace('/<login2see>(.*?)<\/login2see>/is', '<div class="login2see"><div class="login2see_alert">' . $this->translator->trans('flarum-ext-login2see.forum.login_to_see', array('{login}' => '<a class="login2see_login">' . $this->translator->trans('core.ref.log_in') . '</a>')) . '</div></div>', $newHTML);

            $event->attributes['contentHtml'] = $newHTML;
        }
    }
}
