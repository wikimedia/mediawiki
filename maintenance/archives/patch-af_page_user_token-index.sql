ALTER TABLE article_feedback
	ADD INDEX aa_page_user_token (aa_page_id, aa_user_text, aa_user_anon_token, aa_revision),
	DROP INDEX aa_user_page_revision;
