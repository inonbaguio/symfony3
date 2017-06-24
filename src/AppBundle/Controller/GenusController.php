<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{

    /**
     * @Route("/genus/new")
     */
    public function newAction()
    {
        $genus = new Genus();

        $genus->setName('Octopus' . rand(1,100));

        $em = $this->getDoctrine()->getManager();
        $em->persist($genus);
        $em->flush();

        return new Response('<html><body>Genus Created!</body></html>');
    }

    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $genuses = $em->getRepository('AppBundle:Genus')
            ->findAllPublishedOrderedBySize();

        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses
        ]);

    }

    /**
     * @Route("/genus/{genusName}", name="genus_show")
     */
    public function showAction($genusName)
    {
        $em = $this->getDoctrine()->getManager();

        $genus = $em->getRepository('AppBundle:Genus')
            ->findOneBy([
                'name' => $genusName
            ]);

        if (!$genus) {
            throw $this->createNotFoundException('genus not found');
        }

        return $this->render('genus/show.html.twig', array(
            'genus' => $genus
        ));
/*        $funFact = 'Octopuses can change the color of their body in just *three-tenths* of a second!';
        $funFact = $this->container->get('markdown.parser')->transform($funFact);
        $cache = $this->container->get('doctrine_cache.providers.my_markdown_cache');
        $key = md5($funFact);

        if ($cache->contains($key)) {
            $funFact = $cache->fetch($key);
        } else {
            sleep(1);
            $funFact = $this->container->get('markdown.parser')
                ->transform($funFact);
            $cache->save($key, $funFact);
        }

        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        $notes = [
            'Octopus asked me a riddle, outsmarted me',
            'I counted 8 legs... as they wrapped around me',
            'Inked!'
        ];*/

    }

    /**
     * @Route("/genus/{genusName}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction($genusName)
    {
        $notes = [
            ['id' => 1, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle, outsmarted me', 'date' => 'Dec. 10, 2015'],
            ['id' => 2, 'username' => 'AquaWeaver', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 legs... as they wrapped around me', 'date' => 'Dec. 1, 2015'],
            ['id' => 3, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked!', 'date' => 'Aug. 20, 2015'],
        ];

        $data = [
            'notes' => $notes
        ];

        return new Response(json_encode($data));
    }
}