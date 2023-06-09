/** @module SectionObserver */

/**
 * @callback OnIntersection
 * @param {HTMLElement} element The section that triggered the new intersection change.
 */

/**
 * @typedef {Object} SectionObserverProps
 * @property {NodeList} elements A list of HTML elements to observe for
 * intersection changes. This list can be updated through the `elements` setter.
 * @property {OnIntersection} onIntersection Called when a new intersection is observed.
 * @property {number} [topMargin] The number of pixels to shrink the top of
 * the viewport's bounding box before calculating intersections. This is useful
 * for sticky elements (e.g. sticky headers). Defaults to 0 pixels.
 * @property {number} [throttleMs] The number of milliseconds that the scroll
 * handler should be throttled.
 */

/**
 * @callback initSectionObserver
 * @param {SectionObserverProps} props
 * @return {SectionObserver}
 */

/**
 * Observe intersection changes with the viewport for one or more elements. This
 * is intended to be used with the headings in the content so that the
 * corresponding section(s) in the table of contents can be "activated" (e.g.
 * bolded).
 *
 * When sectionObserver notices a new intersection change, the
 * `props.onIntersection` callback will be fired with the corresponding section
 * as a param.
 *
 * Because sectionObserver uses a scroll event listener (in combination with
 * IntersectionObserver), the changes are throttled to a default maximum rate of
 * 200ms so that the main thread is not excessively blocked.
 * IntersectionObserver is used to asynchronously calculate the positions of the
 * observed tags off the main thread and in a manner that does not cause
 * expensive forced synchronous layouts.
 *
 * @param {SectionObserverProps} props
 * @return {SectionObserver}
 */
module.exports = function sectionObserver( props ) {
	props = Object.assign( {
		topMargin: 0,
		throttleMs: 200,
		onIntersection: () => {}
	}, props );

	let /** @type {number | undefined} */ timeoutId;
	let /** @type {HTMLElement | undefined} */ current;

	const observer = new IntersectionObserver( ( entries ) => {
		let /** @type {IntersectionObserverEntry | undefined} */ closestNegativeEntry;
		let /** @type {IntersectionObserverEntry | undefined} */ closestPositiveEntry;
		const topMargin = /** @type {number} */ ( props.topMargin );

		entries.forEach( ( entry ) => {
			const top =
					entry.boundingClientRect.top - topMargin;
			if (
				top > 0 &&
				(
					closestPositiveEntry === undefined ||
					top < closestPositiveEntry.boundingClientRect.top - topMargin
				)
			) {
				closestPositiveEntry = entry;
			}

			if (
				top <= 0 &&
				(
					closestNegativeEntry === undefined ||
					top > closestNegativeEntry.boundingClientRect.top - topMargin
				)
			) {
				closestNegativeEntry = entry;
			}
		} );

		const closestTag =
			/** @type {HTMLElement} */ ( closestNegativeEntry ? closestNegativeEntry.target :
				/** @type {IntersectionObserverEntry} */ ( closestPositiveEntry ).target
			);

		// If the intersection is new, fire the `onIntersection` callback.
		if ( current !== closestTag ) {
			props.onIntersection( closestTag );
		}
		current = closestTag;

		// When finished finding the intersecting element, stop observing all
		// observed elements. The scroll event handler will be responsible for
		// throttling and reobserving the elements again. Because we don't have a
		// wrapper element around our content headings and their children, we can't
		// rely on IntersectionObserver (which is optimized to detect intersecting
		// elements *within* the viewport) to reliably fire this callback without
		// this manual step. Instead, we offload the work of calculating the
		// position of each element in an efficient manner to IntersectionObserver,
		// but do not use it to detect when a new element has entered the viewport.
		observer.disconnect();
	} );

	/**
	 * Calculate the intersection of each observed element.
	 */
	function calcIntersection() {
		// IntersectionObserver will asynchronously calculate the boundingClientRect
		// of each observed element off the main thread after `observe` is called.
		props.elements.forEach( ( element ) => {
			if ( !element.parentNode ) {
				mw.log.warn( 'Element being observed is not in DOM', element );
				return;
			}
			observer.observe( /** @type {HTMLElement} */ ( element ) );
		} );
	}

	function handleScroll() {
		// Throttle the scroll event handler to fire at a rate limited by `props.throttleMs`.
		if ( !timeoutId ) {
			timeoutId = window.setTimeout( () => {
				calcIntersection();
				timeoutId = undefined;
			}, props.throttleMs );
		}
	}

	function bindScrollListener() {
		window.addEventListener( 'scroll', handleScroll );
	}

	function unbindScrollListener() {
		window.removeEventListener( 'scroll', handleScroll );
	}

	/**
	 * Pauses intersection observation until `resume` is called.
	 */
	function pause() {
		unbindScrollListener();
		clearTimeout( timeoutId );
		timeoutId = undefined;
		// Assume current is no longer valid while paused.
		current = undefined;
	}

	/**
	 * Resumes intersection observation.
	 */
	function resume() {
		bindScrollListener();
	}

	/**
	 * Cleans up event listeners and intersection observer. Should be called when
	 * the observer is permanently no longer needed.
	 */
	function unmount() {
		unbindScrollListener();
		observer.disconnect();
	}

	/**
	 * Set a list of HTML elements to observe for intersection changes.
	 *
	 * @param {NodeList} list
	 */
	function setElements( list ) {
		props.elements = list;
	}

	bindScrollListener();

	/**
	 * @typedef {Object} SectionObserver
	 * @property {calcIntersection} calcIntersection
	 * @property {pause} pause
	 * @property {resume} resume
	 * @property {unmount} unmount
	 * @property {setElements} setElements
	 */
	return {
		calcIntersection,
		pause,
		resume,
		unmount,
		setElements
	};
};
