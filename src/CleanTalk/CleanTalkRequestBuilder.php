<?php

namespace MakeItFly\CleanTalkBundle\CleanTalk;

use Cleantalk\CleantalkRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CleanTalkRequestBuilder implements CleanTalkRequestBuilderInterface
{
    private string $authKey;

    private CleantalkRequest $request;

    private string $agent;

    private RequestStack $requestStack;

    public function __construct(
        string $authKey,
        string $agent,
        RequestStack $requestStack
    ) {
        $this->authKey = $authKey;
        $this->agent = $agent;
        $this->requestStack = $requestStack;
    }

    public function build(): self
    {
        $this->request = new CleantalkRequest();
        $this->request->auth_key = $this->authKey;
        $this->request->agent = $this->agent;

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest !== null) {
            $this->initializeFromRequest($currentRequest);
        }

        return $this;
    }

    public function setSenderEmail(string $email): CleanTalkRequestBuilderInterface
    {
        $this->request->sender_email = $email;

        return $this;
    }

    public function setSenderIp(string $ip): CleanTalkRequestBuilderInterface
    {
        $this->request->sender_ip = $ip;

        return $this;
    }

    public function setSenderNickname(string $nickname): CleanTalkRequestBuilderInterface
    {
        $this->request->sender_nickname = $nickname;

        return $this;
    }

    public function setPhone(string $phone): CleanTalkRequestBuilderInterface
    {
        $this->request->phone = $phone;

        return $this;
    }

    public function setJsOn(bool $jsOn): CleanTalkRequestBuilderInterface
    {
        $this->request->js_on = $jsOn;

        return $this;
    }

    public function setMessage(string $message): CleanTalkRequestBuilderInterface
    {
        $this->request->message = $message;

        return $this;
    }

    public function getRequest(): CleantalkRequest
    {
        $request = $this->request;
        $request->submit_time = (new \DateTime())->getTimestamp();

        // Reset the state.
        $this->build();

        return $request;
    }

    private function initializeFromRequest(Request $request): void
    {
        $this->request->sender_ip = $request->getClientIp();

        $allHeaders = $request->headers->all();
        $this->request->all_headers = json_encode($allHeaders);

        $senderInfo = [];
        if ($request->server->has('HTTP_REFERER')) {
            $senderInfo['REFERRER'] = $request->server->get('HTTP_REFERER');
        }
        if ($request->server->has('HTTP_USER_AGENT')) {
            $senderInfo['USER_AGENT'] = $request->server->get('HTTP_USER_AGENT');
        }

        // Both fields are mandatory.
        if (count($senderInfo) === 2) {
            $this->request->sender_info = json_encode($senderInfo);
        }
    }
}
