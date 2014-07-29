<?php
/**
 * Class for notification functionalities
 *
 * @property int    $status The status of the notification (read, unread)
 * @property string $event  String "action:type:subtype" that can be used
 *                          to add more subjects to a notification later
 */
class ElggNotification extends ElggObject {

	const HAS_ACTOR = "hasActor";
	const HAS_OBJECT = "hasObject";

	/** Override */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "notification";
	}

	/**
	 * Set the user triggering the notification
	 *
	 * @param ElggUser $user
	 * @return bool
	 */
	public function setSubject ($user) {
		return $this->addRelationship($user->guid, self::HAS_ACTOR);
	}

	/**
	 * Set the object involved in the notification
	 *
	 * @param ElggEntity $entity
	 * @return bool
	 */
	public function setTarget ($entity) {
		return $this->addRelationship($entity->guid, self::HAS_OBJECT);
	}

	/**
	 * Get the object of the notification
	 *
	 * @return ElggObject $object
	 */
	public function getTarget () {
		$object = $this->getEntitiesFromRelationship(array('relationship' => self::HAS_OBJECT));
		if ($object) {
			$object = $object[0];
		}

		return $object;
	}

	/**
	 * Get the user who triggered the notification
	 *
	 * @return ElggUser $subject
	 */
	public function getSubject () {
		$subject = $this->getSubjects();
		if ($subject) {
			$subject = $subject[0];
		}

		return $subject;
	}

	/**
	 * Get all users who participate in the notification
	 *
	 * @return ElggUser[]|false
	 */
	public function getSubjects() {
		return $this->getEntitiesFromRelationship(array('relationship' => self::HAS_ACTOR));
	}

	/**
	 * Mark this notification as read
	 *
	 * @return void
	 */
	public function markRead() {
		$this->status = 'read';
	}

	/**
	 * Mark this notification as unread
	 *
	 * @return void
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

		$success = parent::save();

		elgg_set_ignore_access($ia);

		return $success;
	}
}