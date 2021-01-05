<?php

namespace App\Model;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
require_once(__DIR__ . '/../../public/absences/class.absences.php');

/**
 * @Entity
 * @Table(name="responsables")
 **/
class Manager extends PLBEntity {
    /** @Id @Column(type="integer", length=11) @GeneratedValue **/
    protected $id;

    /** @Column(type="integer", length=11) **/
    protected $perso_id;

    /** @Column(type="integer", length=11) **/
    protected $responsable;

    /** @Column(type="integer", length=1) **/
    protected $notification;

    /**
     * @ManyToOne(targetEntity="Agent",inversedBy="responsables")
     * @JoinColumn(name="perso_id", referencedColumnName="id")
     */
    protected $agent;

}
