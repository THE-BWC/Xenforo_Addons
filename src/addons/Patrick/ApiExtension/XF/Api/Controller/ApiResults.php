<?php

namespace Patrick\ApiExtension\XF\API\Controller;

class ApiResults
{
    public function apiBoolResult(bool $success, array $extra = [])
    {
        return $this->apiResult(['success' => $success] + $extra);
    }

    public function apiResult($result)
    {
        if (is_array($result))
        {
            $result = new \XF\Api\Result\ArrayResult($result);
        }
        elseif ($result instanceof \XF\Mvc\Entity\Entity)
        {
            $result = $result->toApiResult();
        }
        else if (!($result instanceof \XF\Api\Result\ResultInterface))
        {
            throw new \LogicException(
                "Must pass \XF\Api\Result\ResultInterface or array to apiResult; received ". gettype($result)
            );
        }

        return new \XF\Api\Mvc\Reply\ApiResult($result);
    }
}