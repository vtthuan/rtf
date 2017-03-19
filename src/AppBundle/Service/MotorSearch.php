<?php

namespace AppBundle\Service;

use Elastica\Query\Match;
use AppBundle\Model\PostSearch;
use FOS\ElasticaBundle\Finder\FinderInterface;

/**
 * Service pour exécuter des requêtes sur Elastic Search.
 */
class MotorSearch
{
    const MIN_CHAR_MDR_POST = 3;
    const LIMIT_MDR_POST = 10;

    private $finderPost;

    public function __construct(FinderInterface $finderPost)
    {
        $this->finderPost = $finderPost;
    }

    /**
     * Exécute la recherche sur Elasticsearch pour le moteur de recherche des catégories.
     *
     * @param string $search Valeur recherchée
     *
     * @return Categorie[]
     */
    public function recherchePosts($search)
    {
        $query = new Match();
        $query->setFieldQuery('title', $search);
        $query->setFieldOperator('title', 'AND');
        return $this->finderPost->find($query, self::LIMIT_MDR_POST);
    }
    
    
    public function getQueryForSearch(PostSearch $postSearch)
    {
        $query = new Match();
        if ($postSearch->getTitle() != null && $postSearch != '') {
            $query->setFieldQuery('post.title', $postSearch->getTitle());
            $query->setFieldFuzziness('post.title', 0.7);
            $query->setFieldMinimumShouldMatch('post.title', '80%');
            return $query;
        }
        return null;
    }
    
    
    public function searchByName(PostSearch $postSearch)
    {
        $query = $this->getQueryForSearch($postSearch);

        return $this->finderPost->find($query);
    }
}