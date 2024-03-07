<?php

namespace Wikimedia\Rdbms;

/** @deprecated since 1.39 use DBConnRef */
class MaintainableDBConnRef extends DBConnRef {
}

/** @deprecated class alias since 1.33 */
class_alias( MaintainableDBConnRef::class, 'MaintainableDBConnRef' );
