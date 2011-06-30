<?php
/*
 * Assume that :
 * - $parentLocation is a valid Location (formerly "parent node") => \ezp\Content\Location
 * - Current default locale is eng-GB.
 * - Locale fre-FR has also been set in the system
 */

use ezp\content\Content;
use ezp\content\ContentType;


$contentType = $repository->getContentTypeService()->loadByIdentifier( 'folder' );
$content = new Content( $contentType );
/*
 * $content->fields have been set to default values from ContentType fields (depends on fieldType)
 * Value manipulation is totally up to the field type
 */
$content->fields["name"] = "My folder name";
// Shortcut for: $content->fields["name"]->value = "My folder name";

$content->fields["description"] = "<p>This is the <strong>HTML description</strong></p>";
// For raw xml: $content->fields["description"]->raw = "<?xml...";

// Another syntax could be valid, from post data.
// the post data follows same convention as structure of potential json import
$content->fromHash( $_POST['content'] );
// Or in case of unique id on existing content: $content->fromHash( $_POST['content']['id'] );

// Now set an fre-FR translation (api has not been defined yet)
$content->addTranslation( "fre-FR" );
$content->translations["fre-FR"]->fields["name"] = "Nom du dossier";
$content->translations["fre-FR"]->fields["description"] = "<p>Ceci est la <strong>description HTML</strong></p>";


// Get the content service from the repository and insert the new content
try
{
    $content->addParent( $parentLocation );
    $publishedContent = $repository->getContentService()->create( $content );
    echo "{$publishedContent}\n"; // Displays content "name" via __toString()
    echo "Content ID is: {$publishedContent->id}\n";
    echo "Content version number is: {$publishedContent->versionNumber}\n";
    // creation date is a DateTime object
    echo "Publication date is: {$publishedContent->creationDate->format( "Y-m-d H:i:s" )}\n";
}
catch ( ezp\Content\ValidationException $e )
{
    echo "Following validation issue occurred with your content : {$e->getMessage()}\n";
    exit;
}

?>
