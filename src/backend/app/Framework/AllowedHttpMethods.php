<?php

declare(strict_types=1);

namespace Emkrawiec\CgrdAssessment\Framework;

enum AllowedHttpMethods
{
    case GET;
    case POST;
    case PUT;
    case DELETE;
}
