<?php
namespace OC\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\BlogBundle\Entity\Skill;


class LoadSkill implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$names = array('Doctrine','Formulaire','twig');
		foreach ($names as $name) {
			$skill = new Skill();
			$skill->setName($name);
			$manager->persist($skill);
		}
		$manager->flush();
	}
}