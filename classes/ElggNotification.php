<?php
/**
 * Extended class for notification functionalities
 *
 * @property int $status The status of the notification (read, unread)
 * @property int $subject_guid User who triggered the notification
 * @property int $target_guid Content that the notification is about
 */
class ElggNotification extends ElggObject {

	/** Override */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "notification";
	}

	/**
	 * Set guid of the user triggering the notification
	 *
	 * @param int $guid
	 */
	public function setSubjectGUID ($guid) {
		$this->subject_guid = $guid;
	}

	/**
	 * Set guid of the notification subject
	 * 
	 * @param int $guid
	 */
	public function setTargetGUID ($guid) {
		$this->target_guid = $guid;
	}

	/**
	 * Get the object of the notification
	 *
	 * @param ElggEntity
	 */
	public function getTargetEntity () {
		return get_entity($this->target_guid);
	}

	/**
	 * Get the user who triggered the notification
	 *
	 * @return ElggUser
	 */
	public function getSubjectEntity () {
		return get_entity($this->subject_guid);
	}

	/**
	 * Mark this notification as read
	 */
	public function markRead() {
		$this->status = 'read';
	}

	/**
	 * Mark this notification as unread
	 */
	public function markUnread() {
		$this->status = 'unread';
	}

	/** Override */
	public function save () {
		// We are writing to someone else's container so ignore access
		$ia = elgg_set_ignore_access(true);
		$this->access_id = ACCESS_PRIVATE;
		$this->status = 'unread';

		parent::save();

		elgg_set_ignore_access($ia);
	}
}