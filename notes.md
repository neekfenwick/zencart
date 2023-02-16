Added email_editor page, copy from define pages. Haven't run it yet.
INSERT IGNORE INTO admin_pages (page_key, language_key, main_page, page_params, menu_key, display_on_menu, sort_order) VALUES 
('emailEditor', 'BOX_TOOLS_EMAIL_EDITOR', 'FILENAME_EMAIL_EDITOR', '', 'tools', 'Y', 13);

Prob not make it RESTful, just stick to old ways. But, do have javascript file email_editor.php for on-page JS goodness. Have to learn some jQuery. What about template preview? Hmm maybe need that REST endpoint!