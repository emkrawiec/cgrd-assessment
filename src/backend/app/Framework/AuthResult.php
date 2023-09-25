<?php

namespace Emkrawiec\CgrdAssessment\Framework;

enum AuthResult
{
    case OK;
    case USER_NOT_FOUND;
    case BAD_CREDENTIALS;
}
