<?php

namespace Splitstack\EnumFriendly\Tests\Enums;

use Splitstack\EnumFriendly\Traits\EnumFriendly;

enum TestStatusStr: string
{
  use EnumFriendly;

  case PENDING = 'pending';
  case IN_PROGRESS = 'in_progress';
  case COMPLETED = 'completed';
}