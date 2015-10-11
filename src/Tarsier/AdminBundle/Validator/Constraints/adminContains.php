<?php
/**
 * Created by PhpStorm.
 * User: Huajie
 * Date: 2015/10/5
 * Time: 17:18
 */

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class adminContains extends Constraint {
    public $message = 'The string "%string%" contains an illegal character: %string2%.';
}