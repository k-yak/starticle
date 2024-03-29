<?php 
class FileManager{
	
	private function getText($texts, $id)
	{
		foreach($texts as $text)
		{
			$myId = (string)$text['id'];
			if($myId == $id) return $text;
		}
		
		return null;
	}
	
	public function getContent($fileName)
	{
		if(file_exists($fileName))
			return simplexml_load_file($fileName);
		else
			throw new Exception("File not found : ".$fileName);
	}
	
	public function createStati18n($xml)
	{
		$fp = fopen($xml->stati18n->file->name,"wb");
		$minify = ("true" == $xml->stati18n->file->minified);
		fwrite($fp, "/*!
 * Generated by Stati18n v0.0.1
 * ".$xml["file"]["name"]."
 * Created by Florian Rotagnon
 * Licensed under MIT
 */
 ");
		foreach($xml->stati18n->language->text as $text)
		{
			$id = $text['id'];
			if(!isset($text['article']) || $text['article'] == "false")
			{
				foreach($text->translation as $translation)
				{
					
					$language = $translation['lang'];
					$data = $translation[0];
					if(!$minify)
					{
						fwrite($fp, "
.stati18n.".$language.".s18n-".$id.":after {
	content: \"".$data."\";
}
");
					}
					else
					{
						fwrite($fp, ".stati18n.".$language.".s18n-".$id.":after{content:\"".$data."\";}");
					}
				
				}
			}
		}
        
        if (isset($xml->stati18n->language->title)) {
            $title = $xml->stati18n->language->title;
            
            foreach($title->translation as $translation)
			{
				$language = $translation['lang'];
				$data = $translation[0];
                
			     if(!$minify)
				{
					fwrite($fp, "
.stati18n.".$language.".s18n-title {
    content: \"".$data."\";
}
");
				}
				else
				{
					fwrite($fp, ".stati18n.".$language.".s18n-title{content:\"".$data."\";}");
				}
			}
        }

				
		fclose($fp);
	}

	public function createStarticle($xml)
	{
		$minify = ("true" == $xml->starticle->file->minified);
		$nbBlock = $xml->starticle->file->block;
		$prefix = $xml->starticle->file->prefix;
		$fp = null;
		$cpt = 0;
		$size = sizeof($xml->starticle->articles->article)-1;
		
		foreach($xml->starticle->articles->article as $article)
		{
			if($cpt % $nbBlock == 0)
			{
				$fileName = $prefix.$cpt/$nbBlock.".css";
				if($cpt != 0) fclose($fp);
				$fp = fopen($fileName,"wb");
				fwrite($fp, "/*!
 * Generated by Starticle v0.0.1
 * ".$fileName."
 * Created by Florian Rotagnon
 * Licensed under MIT
 */
 ");
			}
			
			if($cpt == 0)
			{
				if(!$minify)
				{
					fwrite($fp,"
#starticles-infos{
	content  : '".$size." ".$nbBlock." ".$xml->host->name."';
 }
					
.article-0#starticle-first{
	display: none;
}

.article-0#starticle-prev{
	display: none;
}");
				}
				else
				{
					fwrite($fp,"#starticles-infos{content:".$size." ".$nbBlock." ".$xml->host->name.";}.article-0#starticle-first{display:none;}.article-0#starticle-prev{display:none;}");
				}
			}
			
			if($cpt == $size)
			{
				if(!$minify)
				{
					fwrite($fp,"
.article-".$cpt."#starticle-last{
	display: none;
}

.article-".$cpt."#starticle-next{
	display: none;
}");
				}
				else fwrite($fp,".article-".$cpt."#starticle-first{display:none;}.article-".$cpt."#starticle-prev{display:none;}");
			}
			
			foreach($article->textId as $textId)
			{
				$id = $textId['id'];
				$refId = $textId[0];
				
				$text = $this->getText($xml->stati18n->language->text, $refId);
				if($text == null)
					throw new Exception("Reference to text : ".$refId." in article : ".$id." doesn't exist");
				
				foreach($text->translation as $translation)
				{
					$language = $translation['lang'];
					$data = $translation[0];
					if(!$minify)
					{
						fwrite($fp, "
.stati18n.starticle.".$language.".star-".$id.".article-".$cpt.":after {
	content: \"".$data."\";
}
");
					}
					else
					{
						fwrite($fp, ".stati18n.starticle.".$language.".star-".$id.".article-".$cpt.":after{content:\"".$data."\";}");
					}
				
				}
			}
			
			foreach($article->text as $text)
			{
				$id = $text['id'];
				$data = $text[0];
				if(!$minify)
					{
						fwrite($fp, "
.starticle.star-".$id.".article-".$cpt.":after {
	content: \"".$data."\";
}
");
					}
					else
					{
						fwrite($fp, ".starticle.star-".$id.".article-".$cpt.":after{content:\"".$data."\";}");
					}
			}
			
			foreach($article->image as $image)
			{
			
			}
			
			$cpt++;
		}
		
		for( $i=0 ; $i < $cpt%$nbBlock ; $i++)
		{
			if(!$minify)
			{
				fwrite($fp, "
.article-".($cpt+$i)." {
display: none;
}
");
			}
			else
			{
				fwrite($fp, ".article-".($cpt+$i)."{display:none;}");
			}
		}
		
		fclose($fp);
	}
}

/*Script start*/
if (2 !== $argc) {
    echo "Usage: php $argv[0] [name.xml]\n";
    exit(1);
}

$fm = new FileManager();

try{
	$domArr = $fm->getContent($argv[1]);
	$fm->createStati18n($domArr);	
	$fm->createStarticle($domArr);	
	
	echo "Stati18n  : compilation success";
}
catch(Exception $e)
{
	echo $e->getMessage();
}

?> 