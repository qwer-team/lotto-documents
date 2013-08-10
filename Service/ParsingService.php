<?php

namespace Qwer\LottoDocumentsBundle\Service;

use Itc\DocumentsBundle\Listener\ContainerAware;

class ParsingService extends ContainerAware
{
    public function check(){
        $draws = $this->getUnresaltedDraws();
        
        if(!count($draws)){
            return;
        }
        foreach($draws as $draw){
            $this->getResult($draw);
        }
        
        $this->em->flush();
    }
    
    /**
     * 
     * @param \Qwer\LottoBundle\Entity\Draw $draw
     */
    private function getResult($draw){
       $type =  $draw->getLottoTime()->getLottoType();
       //lotto_documents_result_parser.
       $parserTag = $type->getParser();//france_loto
       if( $parserTag == ''){
           return;
       }
       $parser = $this->container->get("lotto_documents_result_parser.".$parserTag);
       $parser->setDraw($draw);
       try{
          $parser->parse();
          if(!$parser->hasResults()){
              //TODO
          }
       } catch (\Exception $e){
           $message = $e->getMessage();
           echo 'vse ploho '.$message."\n";
       }
    }
    private function getUnresaltedDraws(){
        $em = $this->container->get("doctrine.orm.entity_manager");
        $draws = $em->getRepository("QwerLottoBundle:Draw")
                ->findUnresaltedDraws();
        return $draws;
    }
}
