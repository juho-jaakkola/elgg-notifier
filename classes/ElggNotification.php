<?php
/**
 * Extended class for notification functionalities
 * 
 * @property int $status The status of the notification (read, unread)
 */
class ElggNotifier extends ElggObject {

	/** Override */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "notification";
	}

	/**
	 * Mark as read
	 */
	public function mardRead() {
		$this->status = (int) 1;
	}
}