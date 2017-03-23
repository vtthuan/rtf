<?php
/**
 * Created by PhpStorm.
 * User: Thuan
 * Date: 23/03/2017
 * Time: 22:38
 */

namespace AppBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use AppBundle\Entity\Favorite;
use Doctrine\ORM\EntityManager;

class MyFavoritesPhraseBlockService extends BaseBlockService {

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;


    private $entityManager;
    /**
     * @param string                   $name
     * @param EngineInterface          $templating
     * @param OrderManagerInterface    $orderManager
     * @param CustomerManagerInterface $customerManager
     * @param SecurityContextInterface $securityContext
     */
    public function __construct($name, EngineInterface $templating, EntityManager $entityManager, SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;

        parent::__construct($name, $templating);
    }


    public function getName() {
        return 'MyFavoritesPhraseBlockService';
    }
    public function getDefaultSettings() {
        return array();
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {

    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

    }

    public function setDefaultSettings(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'template' => 'favorite/favorites.html.twig',
            'title' => 'les phrases préférés'
        ));
    }
    public function execute(BlockContextInterface $blockContext, Response $response = null) {
        $settings = $blockContext->getSettings();

        $user = $this->securityContext->getToken()->getUser();

        $repository= $this->entityManager->getRepository(Favorite::getEntityName());
        $favorites = $repository->findBy(array('user' => $user));

        return $this->renderResponse($blockContext->getTemplate(), array(
            'favorites'   => $favorites,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ), $response);

    }
}
