<?php

namespace Splitstack\EnumFriendly\Tests\Enums;

use Splitstack\EnumFriendly\Traits\EnumFriendly;

enum TestStatusInt: int
{
  use EnumFriendly;

  case PENDING = 1;
  case IN_PROGRESS = 2;
  case COMPLETED = 3;
}