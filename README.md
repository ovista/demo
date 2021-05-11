Demo

This module does the following:

Creates a custom fieldable content entity type called Card with the following fields:

Name: Textfield - This is the administration name of the entity.
Heading: Textfield - 255 characters
Body: Formatted text with summary
CTA: Link
Card Image: Media

Administration: Structure -> Card Settings
List: Structure -> Card List

Creates 2 view modes for the Card entity: Landscape and Portrait 
(for testing purposes I am only displaying a different field in each display mode)

Create a custom block called "Card listing block" with the following fields:

Orientation: Select list - can be Portrait or Landscape
Heading: Textfield - 255 characters
Cards: Entity reference auto-complete - can add multiple values by using a comma

The block can be added using both block layout or the layout builder and it displays the following fields:

Heading
Cards - Displays the rendered entity utilizing the view mode selected in the orientation field

The block display uses a custom theme and a twig template that can be customized 
([module]/templates/card-listing-block)

Although the module creates permissions (view/edit/delete etc.) for the new entity type, 
the permissions are not configured by role