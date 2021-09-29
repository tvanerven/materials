<?php

namespace App\Script;

use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use SimpleXMLElement;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Concept;
use App\Entity\Material;
use App\Entity\Relation;
use App\Entity\RelationType;

/*
* @package App
*/

class OntologyImport extends Command
{

    const DATABASE_SCHEMA = 'sql/materials-browser.sql';
    const ONTOLOGY_FILE_PATH = 'asset/t4fs.owl';
    const MATERIALS_FILE_PATH = 'asset/materials.json';
    const SUB_CLASS_OF = 'subClassOf';

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
        $this->cleanDatabase();

        $xmlOntology = $this->getXmlOntology();

        $this->addConcepts($xmlOntology);
        $this->addRelationType($xmlOntology);
        $this->addRelation($xmlOntology);
        $this->addMaterials();

        return 0;
    }

    private function addMaterials()
    {
        $fileMaterials = file_get_contents(self::MATERIALS_FILE_PATH);
        $fileMaterials = json_decode($fileMaterials);

        foreach ($fileMaterials as $fileMaterial) {
            $concepts = $this->em->getRepository(Concept::class)->findBy(['rdfAbout' => $fileMaterial->concept]);

            $material = new Material($fileMaterial->name, $fileMaterial->author, $fileMaterial->doi);
            $material->addConcepts($concepts);
            $this->em->persist($material);
        }
        $this->em->flush();
    }

    private function addConcepts(SimpleXMLElement $xmlOntology)
    {
        foreach ($xmlOntology->children() as $xmlElement) {
            if ($xmlElement->getName() !== 'owl_Class') {
                continue;
            }

            $label = $xmlElement->rdfs_label->__toString();
            $label = preg_replace('/\s+/', ' ', $label);
            $label = trim($label);

            $rdfAbout = $xmlElement->attributes()->rdf_about->__toString();
            $rdfAbout = trim($rdfAbout);

            $concept = new Concept($label, $rdfAbout);
            $this->em->persist($concept);
            print("Insert concept \"$label\" into database\n");
        }

        $this->em->flush();
    }

    private function addRelationType(SimpleXMLElement $xmlOntology)
    {
        $this->em->persist(new RelationType(self::SUB_CLASS_OF));

        foreach ($xmlOntology->children() as $xmlElement) {
            if ($xmlElement->getName() !== 'owl_ObjectProperty') {
                continue;
            }

            $label = $xmlElement->rdfs_label->__toString();
            $label = preg_replace('/\s+/', ' ', $label);
            $label = trim($label);

            $rdfAbout = $xmlElement->attributes()->rdf_about->__toString();
            $rdfAbout = trim($rdfAbout);

            $relationType = new RelationType($label, $rdfAbout);
            $this->em->persist($relationType);
            print("Insert relation type \"$label\" into database\n");
        }

        $this->em->flush();
    }

    private function addRelation(SimpleXMLElement $xmlOntology)
    {
        foreach ($xmlOntology->children() as $xmlElement) {
            if ($xmlElement->getName() !== 'owl_Class') {
                continue;
            }

            foreach ($xmlElement as $xmlSubElement) {
                if ($xmlSubElement->getName() !== 'rdfs_subClassOf') {
                    continue;
                }

                $rdfAboutSource = $xmlElement->attributes()->rdf_about->__toString();
                $rdfAboutTarget = null;
                $rdfAboutRelation = null;

                if ($xmlSubElement->attributes()->rdf_resource == null) {
                    if (isset($xmlSubElement->owl_Restriction->owl_someValuesFrom)) {
                        $rdfAboutTarget = $xmlSubElement->owl_Restriction->owl_someValuesFrom;
                    } else {
                        $rdfAboutTarget = $xmlSubElement->owl_Restriction->owl_allValuesFrom;
                    }

                    $rdfAboutTarget = $rdfAboutTarget->attributes()->rdf_resource->__toString();
                    $rdfAboutRelation = $xmlSubElement->owl_Restriction->owl_onProperty->attributes()->rdf_resource->__toString();
                } else {
                    $rdfAboutTarget = $xmlSubElement->attributes()->rdf_resource->__toString();
                    $rdfAboutRelation = null;
                }

                $rdfAboutSource = trim($rdfAboutSource);
                $rdfAboutTarget = trim($rdfAboutTarget);

                $relationType = $this->em->getRepository(RelationType::class)->findOneBy(['rdfAbout' => $rdfAboutRelation]);

                $conceptSource = $this->em->getRepository(Concept::class)->findOneBy(['rdfAbout' => $rdfAboutSource]);
                $conceptTarget = $this->em->getRepository(Concept::class)->findOneBy(['rdfAbout' => $rdfAboutTarget]);

                if ($conceptTarget == null) {
                    "Concept <$rdfAboutTarget> doesn't exist!";
                    continue;
                }

                $relation = new Relation($conceptSource, $conceptTarget, $relationType);

                if (!$this->relationExists($relation)) {
                    $this->em->persist($relation);
                    $this->em->flush();
                    print("Insert relation: {$conceptSource->getLabel()} -> {$relationType->getLabel()} -> {$conceptTarget->getLabel()}\n");
                } else {
                    print("Error to insert relation (duplicate entry): $rdfAboutSource -> {$relationType->getLabel()} -> $rdfAboutTarget\n");
                }
            }
        }
    }

    private function relationExists(Relation $relation): bool
    {
        $existingRelation = $this->em->getRepository(Relation::class)->findOneBy([
            'source' => $relation->getSource(),
            'target' => $relation->getTarget(),
            'relationType' => $relation->getRelationType()
        ]);

        return $existingRelation != null;
    }

    private function getXmlOntology(): SimpleXMLElement
    {
        $ontologyFileContent = file_get_contents(self::ONTOLOGY_FILE_PATH);
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

        return simplexml_load_string($ontologyFileContent);
    }

    private function cleanDatabase()
    {
        $databaseSchema = file_get_contents(self::DATABASE_SCHEMA);
        $this->em->getConnection()->executeQuery($databaseSchema);
        $this->em->flush();
    }
}
