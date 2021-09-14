<?php

namespace MediaWiki\Hook;

use MediaWiki\MediaWikiServices;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MediaWikiServices" to register handlers implementing this interface.
 *
 * @warning Implementations of this interface must not have services injected into
 * their constructor! This is because this hook runs while the service container is
 * still being initialized, so any services it asks for might get instantiated based on
 * incomplete configuration and wiring.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MediaWikiServicesHook {
	/**
	 * This hook is called when a global MediaWikiServices instance is initialized.
	 * Extensions may use this to define, replace, or wrap services. However, the
	 * preferred way to define a new service is the $wgServiceWiringFiles array.
	 *
	 * @warning Implementations must not immediately access services instances from the
	 * service container $services, since the service container is not fully initialized
	 * at the time when the hook is called. However, callbacks that are used as service
	 * instantiators or service manipulators may access service instances.
	 *
	 * Example:
	 * @code
	 * function onMediaWikiServices( $services ) {
	 *     // The service wiring and configuration in $services may be incomplete at this time,
	 *     // do not access services yet!
	 *     // At this point, we can only manipulate the wiring, not use it!
	 *
	 *     $services->defineService(
	 *        'MyCoolService',
	 *         function( MediaWikiServices $container ) {
	 *             // It's ok to access services inside this callback, since the
	 *             // service container will be fully initialized when it is called!
	 *             return new MyCoolService( $container->getPageLookup() );
	 *         }
	 *     );
	 *
	 *     $services->addServiceManipulator(
	 *         'SlotRoleRegistry',
	 *         function ( SlotRoleRegistry $service, MediaWikiServices $container ) {
	 *             // ...
	 *         }
	 *     );
	 *
	 *     $services->redefineService(
	 *         'StatsdDataFactory',
	 *         function ( MediaWikiServices $container ) {
	 *             // ...
	 *         }
	 *     );
	 * }
	 * @endcode
	 *
	 * @since 1.35
	 *
	 * @param MediaWikiServices $services
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMediaWikiServices( $services );
}
