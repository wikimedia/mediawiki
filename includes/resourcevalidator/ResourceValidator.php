<?php

interface ResourceValidator
{
	function validateResource( $resource );
	function validateResources( $resources );
	function accept( ResourceLoaderModule $resourceLoaderModule );
	function validateResourceLoaderModule( ResourceLoaderModule $resourceLoaderModule );
}
