<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Form\Factory;

use Symfony\Component\Form\FormInterface;

interface FactoryInterface
{
    /**
     * @return FormInterface
     */
    public function createForm();
}
