<?php defined('BASEPATH') OR exit('No direct script access allowed');

// General
$lang['albums:files_title']					= 'Files';
$lang['albums:fetching']						= 'Retrieving data...';
$lang['albums:fetch_completed']				= 'Completed';
$lang['albums:save_failed']					= 'Sorry. The changes could not be saved';
$lang['albums:item_created']					= '"%s" was created';
$lang['albums:item_updated']					= '"%s" was updated';
$lang['albums:item_deleted']					= '"%s" was deleted';
$lang['albums:item_not_deleted']				= '"%s" could not be deleted';
$lang['albums:item_not_found']				= 'Sorry. "%s" could not be found';
$lang['albums:sort_saved']					= 'Sort order saved';
$lang['albums:no_permissions']				= 'You do not have sufficient permissions';

// Labels
$lang['albums:activity']						= 'Activity';
$lang['albums:places']						= 'Event\'s Album Place';
$lang['albums:back']							= 'Back';
$lang['albums:forward']						= 'Forward';
$lang['albums:start']						= 'Start Upload';
$lang['albums:details']						= 'Details';
$lang['albums:id']							= 'ID';
$lang['albums:name']							= 'Name';
$lang['albums:slug']							= 'Slug';
$lang['albums:path']							= 'Path';
$lang['albums:added']						= 'Date Added';
$lang['albums:width']						= 'Width';
$lang['albums:height']						= 'Height';
$lang['albums:ratio']						= 'Ratio';
$lang['albums:alt_attribute']				= 'Alt Attribute';
$lang['albums:full_size']					= 'Full Size';
$lang['albums:filename']						= 'Filename';
$lang['albums:filesize']						= 'Filesize';
$lang['albums:download_count']				= 'Download Count';
$lang['albums:download']						= 'Download';
$lang['albums:location']						= 'Location';
$lang['albums:keywords']						= 'Keywords';
$lang['albums:toggle_data_display']			= 'Toggle Data Display'; #translate
$lang['albums:description']					= 'Description';
$lang['albums:container']					= 'Container';
$lang['albums:bucket']						= 'Bucket';
$lang['albums:check_container']				= 'Check Validity';
$lang['albums:search_message']				= 'Type and hit Enter';
$lang['albums:search']						= 'Search';
$lang['albums:synchronize']					= 'Synchronize';
$lang['albums:uploader']						= 'Drop files here <br />or<br />Click to select files';
$lang['albums:replace_file']					= 'Replace file';

// Context Menu
$lang['albums:refresh']						= 'Refresh'; #translate
$lang['albums:open']							= 'Open';
$lang['albums:new_album']					= 'New album';
$lang['albums:upload']						= 'Upload';
$lang['albums:rename']						= 'Rename';
$lang['albums:replace']						= 'Replace';
$lang['albums:delete']						= 'Delete';
$lang['albums:edit']							= 'Edit';
$lang['albums:details']						= 'Details';

// albums

$lang['albums:no_albums']					= 'Albums are managed much like they would be on your desktop. Right click in the area below this message to create your first album. Then right click on the album to rename, delete, upload files to it, or change details such as linking it to a cloud location.';
$lang['albums:no_album_places']                                 = 'Albums that you create will show up here in a tree that can be expanded and collapsed. Click on "Places" to view the root album.';
$lang['albums:no_albums_wysiwyg']			= 'No albums have been created yet';
$lang['albums:new_album_name']				= 'Untitled album';
$lang['albums:album']						= 'album';
$lang['albums:albums']						= 'albums';
$lang['albums:select_album']				= 'Select a album';
$lang['albums:subalbums']					= 'Subalbums';
$lang['albums:root']							= 'Root';
$lang['albums:no_subalbums']				= 'No Subalbums';
$lang['albums:album_not_empty']				= 'You must delete the contents of "%s" first';
$lang['albums:mkdir_error']					= 'We are unable to create %s. You must create it manually';
$lang['albums:chmod_error']					= 'The upload directory is unwriteable. It must be 0777';
$lang['albums:location_saved']				= 'The album location has been saved';
$lang['albums:container_exists']				= '"%s" exists. Save to link its contents to this album';
$lang['albums:container_not_exists']			= '"%s" does not exist in your account. Save and we will try to create it';
$lang['albums:error_container']				= '"%s" could not be created and we could not determine the reason';
$lang['albums:container_created']			= '"%s" has been created and is now linked to this album';
$lang['albums:unwritable']					= '"%s" is unwritable, please set its permissions to 0777';
$lang['albums:specify_valid_album']			= 'You must specify a valid album to upload the file to';
$lang['albums:enable_cdn']					= 'You must enable CDN for "%s" via your Rackspace control panel before we can synchronize';
$lang['albums:synchronization_started']		= 'Starting synchronization';
$lang['albums:synchronization_complete']		= 'Synchronization for "%s" has been completed';
$lang['albums:untitled_album']				= 'Untitled album';

// Files
$lang['albums:no_files']						= 'No files found';
$lang['albums:file_uploaded']				= '"%s" has been uploaded';
$lang['albums:unsuccessful_fetch']			= 'We were unable to fetch "%s". Are you sure it is a public file?';
$lang['albums:invalid_container']			= '"%s" does not appear to be a valid container.';
$lang['albums:no_records_found']				= 'No records could be found';
$lang['albums:invalid_extension']			= '"%s" has a file extension that is not allowed';
$lang['albums:upload_error']					= 'The file upload failed';
$lang['albums:description_saved']			= 'The file details have been saved';
$lang['albums:alt_saved']					= 'The image alt attribute has been saved';
$lang['albums:file_moved']					= '"%s" has been moved successfully';
$lang['albums:exceeds_server_setting']		= 'The server cannot handle this large of a file';
$lang['albums:exceeds_allowed']				= 'File exceeds the max size allowed';
$lang['albums:file_type_not_allowed']		= 'This type of file is not allowed';
$lang['albums:replace_warning']				= 'Warning: Do not replace a file with a file of a different type (e.g. .jpg with .png)';
$lang['albums:type_a']						= 'Audio';
$lang['albums:type_v']						= 'Video';
$lang['albums:type_d']						= 'Document';
$lang['albums:type_i']						= 'Image';
$lang['albums:type_o']						= 'Other';

/* End of file files_lang.php */