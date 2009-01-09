<?php
declare(ENCODING = 'utf-8');
namespace F3\FLOW3\Cache;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * @package FLOW3
 * @subpackage Cache
 * @version $Id$
 */

/**
 * An abstract cache
 *
 * @package FLOW3
 * @subpackage Cache
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser Public License, version 3 or later
 * @scope prototype
 */
abstract class AbstractCache implements \F3\FLOW3\Cache\CacheInterface {

	const TAG_CLASS = '%CLASS%';

	/**
	 * @var string Identifies this cache
	 */
	protected $identifier;

	/**
	 * @var \F3\FLOW3\Cache\AbstractBackend
	 */
	protected $backend;

	/**
	 * Constructs the cache
	 *
	 * @param string $identifier A identifier which describes this cache
	 * @param \F3\FLOW3\Cache\BackendInterface $backend Backend to be used for this cache
	 * @author Robert Lemke <robert@typo3.org>
	 * @throws \InvalidArgumentException if the identifier doesn't match PATTERN_IDENTIFIER
	 */
	public function __construct($identifier, \F3\FLOW3\Cache\BackendInterface $backend) {
		if (!preg_match(self::PATTERN_IDENTIFIER, $identifier)) throw new \InvalidArgumentException('"' . $identifier . '" is not a valid cache identifier.', 1203584729);
		$this->identifier = $identifier;
		$this->backend = $backend;
		$this->backend->setCache($this);
	}

	/**
	 * Returns this cache's identifier
	 *
	 * @return string The identifier for this cache
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * Returns the backend used by this cache
	 *
	 * @return \F3\FLOW3\Cache\AbstractBackend The backend used by this cache
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getBackend() {
		return $this->backend;
	}

	/**
	 * Removes all cache entries of this cache.
	 *
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function flush() {
		$this->backend->flush();
	}

	/**
	 * Removes all cache entries of this cache which are tagged by the specified tag.
	 *
	 * @param string $tag The tag the entries must have
	 * @return void
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function flushByTag($tag) {
		$this->backend->flushByTag($tag);
	}

	/**
	 * Does garbage collection
	 *
	 * @return void
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function collectGarbage() {
		$this->backend->collectGarbage();
	}

	/**
	 * Renders a tag which can be used to mark a cache entry as "depends on this class".
	 * Whenever the specified class is modified, all cache entries tagged with the
	 * class are flushed.
	 *
	 * @param string $className The class name
	 * @return string Class Tag
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getClassTag($className) {
		return self::TAG_CLASS . str_replace('\\', '_', $className);
	}

}
?>