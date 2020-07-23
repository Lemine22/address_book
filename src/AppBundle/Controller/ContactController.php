<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use AppBundle\Service\CountryNamer;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Contact controller.
 *
 * @Route("")
 */
class ContactController extends Controller
{
    private $objectManager;
    private $paginator;

    public function __construct(ObjectManager $objectManager, PaginatorInterface $paginator)
    {
        $this->objectManager = $objectManager;
        $this->paginator = $paginator;
    }

    /**
     * Lists all contacts with a pagination system 
     *
     * @Route("/", name="contact_index")
     * @Method("GET")
     */
    public function index(Request $request)
    {
        $pagination = $this->paginator->paginate(
            $this->objectManager->getRepository(Contact::class)->findAll(),
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('contact/index.html.twig', [
            'contacts' => $pagination,
         ]);
    }

    /**
     * Creates a new contact.
     *
     * @Route("/new", name="contact_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, CountryNamer $countryNamer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        $country = $form->getData()->getCountry();
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $contact->setCountry($countryNamer->getCountryName($country));
            $this->objectManager->persist($contact);
            $this->objectManager->flush();
            $this->addFlash('success', 'Contact creatd successfully!');
            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a contact with slug.
     *
     * @Route("/{slug}-{id}", name="contact_show", requirements={"slug": "[a-z0-9\-]*"})
     * @Method("GET")
     */
    public function show(Contact $contact, string $slug): Response
    {
        if($contact->getSlug() !== $slug) {
            return $this->redirectToRoute('contact_show', [
                'id' => $contact->getId(),
                'slug' => $contact->getSlug()
            ], 301);
        }
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * Displays a form to edit an existing contact.
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Contact $contact, CountryNamer $countryNamer): Response
    {
      
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        $country = $form->getData()->getCountry();

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCountry($countryNamer->getCountryName($country));

            $this->objectManager->flush();
            $this->addFlash('success', 'Contact updated!');
            return $this->redirectToRoute('contact_show', [
                'id' => $contact->getId(),
                'slug' => $contact->getSlug()
            ]);
        }

        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'edit_form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a contact.
     *
     * @Route("/{id}/delete", name="contact_delete")
     * @Method("DELETE")
     */
    public function delete(Contact $contact, Request $request): Response
    {
            if($this->isCsrfTokenValid('delete' . $contact->getId(), $request->get('_token'))){

                $this->objectManager->remove($contact);
                $this->objectManager->flush();
                $this->addFlash('success', 'Contact deleted!');
                return $this->redirectToRoute('contact_index');
            }
    }

}
