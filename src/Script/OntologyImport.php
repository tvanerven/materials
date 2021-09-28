<?php

namespace App\Script;

use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use SimpleXMLElement;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
* @package App
*/
class OntologyImport extends Command
{

    const ONTOLOGY_FILE_NAME = 't4fs.owl';

    /**
     * @var string
     */
    protected static $defaultName = "app:import-ontology";

    /**
     * @var EntityManagerInterface Doctrine entity manager
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription("To fill database with element in ontology owl file.");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $ontologyFileContent = file_get_contents(self::ONTOLOGY_FILE_NAME);
        $ontologyFileContent = str_replace('base:', 'base_', $ontologyFileContent);
        $ontologyFileContent = str_replace('dc:', 'dc_', $ontologyFileContent);
        $ontologyFileContent = str_replace('obo:', 'obo_', $ontologyFileContent);
        $ontologyFileContent = str_replace('owl:', 'owl_', $ontologyFileContent);
        $ontologyFileContent = str_replace('rdf:', 'rdf_', $ontologyFileContent);
        $ontologyFileContent = str_replace('xml:', 'xml_', $ontologyFileContent);
        $ontologyFileContent = str_replace('xsd:', 'xsd_', $ontologyFileContent);
        $ontologyFileContent = str_replace('rdfs:', 'rdfs_', $ontologyFileContent);
        $ontologyFileContent = str_replace('skos:', 'skos_', $ontologyFileContent);
        $ontologyFileContent = str_replace('terms:', 'terms_', $ontologyFileContent);
        $ontologyFileContent = str_replace('oboInOwl:', 'oboInOwl_', $ontologyFileContent);
        $ontologyFileContent = str_replace('FAIRterminology:', 'FAIRterminology_', $ontologyFileContent);

        $xmlOntology = simplexml_load_string($ontologyFileContent);

        foreach ($xmlOntology->children() as $xmlElement) {
            if ($xmlElement->getName() === 'owl_Class') {

                $rdfId = $xmlElement->attributes()->rdf_about;

                $label = $xmlElement->rdfs_label->__toString();
                $label = preg_replace('/\s+/', ' ', $label);
                $label = trim($label);
            }
        }

        return 0;
    }
}
