<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Class Kernel.
 *
 * This is the main kernel class for the Symfony application, extending the base kernel functionality.
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
