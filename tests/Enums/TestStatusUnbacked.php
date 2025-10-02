<?php

namespace Splitstack\EnumFriendly\Tests\Enums;

use Splitstack\EnumFriendly\Traits\EnumFriendly;

enum TestStatusUnbacked
{
  use EnumFriendly;

  case PENDING;
  case IN_PROGRESS;
  case COMPLETED;
}
