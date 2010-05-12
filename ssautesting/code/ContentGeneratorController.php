<?php
/**

Copyright (c) 2009, SilverStripe Australia Limited - www.silverstripe.com.au
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the 
      documentation and/or other materials provided with the distribution.
    * Neither the name of SilverStripe nor the names of its contributors may be used to endorse or promote products derived from this software 
      without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE 
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE 
GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY 
OF SUCH DAMAGE.
 
 */
 
class ContentGeneratorController extends Controller
{
	static $url_handlers = array(
		'' => 'index',
		'generate/$Total/$Parent' => 'generate',
		'$Action' => '$Action',
		'$Action//$Action/$ID' => 'handleAction',
	);
	
	public function index($request)
	{
	}

	public function generate($request)
	{
		$numberToCreate = (int) $this->urlParams['Total'];
		$parentId = (int) $this->urlParams['Parent'];
		
		if (!$parentId) {
			throw new Exception("Invalid parent specified");
		}
		
		$parent = DataObject::get_one('SiteTree', 'SiteTree.ID = '.Convert::raw2sql($parentId));
		if (!$parent) {
			throw new Exception("Parent #$parentId not found");
		}
		
		// create new children
		$total = 0;
		$childPointer = 0;
		$allChildren = array();
		while(true) {
			$total = count($allChildren);
			$toCreate = ($numberToCreate - $total < 15) ?  $numberToCreate - $total : 15;
			// first go and create a bunch of children
			$children = $this->createChildren($parent, $toCreate);
			$allChildren = array_merge($allChildren, $children);
			
			$total = count($allChildren);
			
			// now go over all the children and create children of them
			if ($total >= $numberToCreate) {
				break;
			}
			
			// set a new parent from the next child from the list of all created children
			$parent = $allChildren[$childPointer++];
		}
		
		echo "Created $total children under #$parentId<br/>";
	}
	
	
	protected function createChildren($parent, $toCreate)
	{
		$created = array();
		
		for ($i = 0; $i < $toCreate; $i++) {
			$newObject = new Page();

			$one = mt_rand(0, 100000);
			$two = mt_rand(0, 100000);
			$title = md5("$one"."$two");
			$newObject->Title = $title; 
			$newObject->MenuTitle = $title;
			$newObject->URLSegment = $title;

			$newObject->setParent($parent);
			$newObject->write();

			$created[] = $newObject;
		}
		
		return $created;
	}
}


?>