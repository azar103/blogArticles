<?php

namespace OC\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleSkill
 *
 * @ORM\Table(name="article_skill")
 * @ORM\Entity(repositoryClass="OC\BlogBundle\Repository\ArticleSkillRepository")
 */
class ArticleSkill
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255)
     */
    private $level;
    
    /**
     *@ORM\ManyToOne(targetEntity="OC\BlogBundle\Entity\Article")
     *@ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     *@ORM\ManyToOne(targetEntity="OC\BlogBundle\Entity\Skill")
     *@ORM\JoinColumn(nullable=false)
     */
    private $skill;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set level.
     *
     * @param string $level
     *
     * @return ArticleSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set article.
     *
     * @param \OC\BlogBundle\Entity\Article $article
     *
     * @return ArticleSkill
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article.
     *
     * @return \OC\BlogBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set skill.
     *
     * @param \OC\BlogBundle\Entity\Skill $skill
     *
     * @return ArticleSkill
     */
    public function setSkill(Skill $skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill.
     *
     * @return \OC\BlogBundle\Entity\Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
