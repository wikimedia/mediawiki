interface CheckboxHack {
	updateAriaExpanded(checkbox: HTMLInputElement, button: HTMLElement): void;
	bindUpdateAriaExpandedOnInput(checkbox: HTMLInputElement, button: HTMLElement): CheckboxHackListeners;
	bindToggleOnClick(checkbox: HTMLInputElement, button: HTMLElement): CheckboxHackListeners;
	bindToggleOnSpaceEnter(checkbox:HTMLInputElement, button:HTMLElement): CheckboxHackListeners;
	bindDismissOnClickOutside(window: Window, checkbox: HTMLInputElement, button: HTMLElement, target: Node): CheckboxHackListeners;
	bindDismissOnFocusLoss(window: Window, checkbox: HTMLInputElement, button: HTMLElement, target: Node): CheckboxHackListeners;
	bind(window: Window, checkbox: HTMLInputElement, button: HTMLElement, target: Node): CheckboxHackListeners;
	unbind(window: Window, checkbox: HTMLInputElement, button: HTMLElement, listeners: CheckboxHackListeners): void;
}

interface CheckboxHackListeners {
	onUpdateAriaExpandedOnInput?: EventListenerOrEventListenerObject;
	onToggleOnClick?: EventListenerOrEventListenerObject;
	onDismissOnClickOutside?: EventListenerOrEventListenerObject;
	onDismissOnFocusLoss?: EventListenerOrEventListenerObject;
}
