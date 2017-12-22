<?php

/**
 * We don't actually have a Revision classes defined currently.
 * We define an alias based $wgUseMCRRevision but phan doesnt know that.
 * So extend the class here so phan knows what is happening.
 */
class Revision extends RevisionMCR {

}
