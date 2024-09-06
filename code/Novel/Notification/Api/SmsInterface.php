<?php
/**
 * PwC India
 *
 * @category Magento
 * @package  HCG_VideoList
 * @author   PwC India
 * @license  GNU General Public License ("GPL") v3.0
 */

namespace Novel\Notification\Api;

/**
 * @api
 */
interface SmsInterface
{
    public const ID          = 'entity_id';
    public const PAYLOAD     = 'payload';
    public const SOURCE_ID   = 'source_id';
    public const MESSAGE     = 'message';
    public const STATUS      = 'status';
    public const REASON      = 'reason';
    public const UPDATES     = 'updates';
    public const CREATED_AT  = 'created_at';
    public const UPDATED_AT  = 'updated_at';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     */
    public function setId($id);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getPayload();

    /**
     * Set ID
     *
     * @param text $payload
     */
    public function setPayload($payload);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getSourceId();

    /**
     * Set ID
     *
     * @param string $sourceId
     */
    public function setSourceId($sourceId);
    
    /**
     * Get ID
     *
     * @return int|null
     */
    public function getMessage();

    /**
     * Set ID
     *
     * @param string $message
     */
    public function setMessage($message);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set ID
     *
     * @param string $status
     */
    public function setStatus($status);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getReason();

    /**
     * Set ID
     *
     * @param string $reason
     */
    public function setReason($reason);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getUpdates();

    /**
     * Set ID
     *
     * @param string $updates
     */
    public function setUpdated($updates);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getCreatedAt();

    /**
     * Set ID
     *
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt);

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getUpdatedAt();

    /**
     * Set ID
     *
     * @param string $updatedAt
     */
    public function setUpdatedAt($updatedAt);
}
