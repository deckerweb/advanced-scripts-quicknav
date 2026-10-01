# Validation status 1.2.0-rc3

* Fixed: Removed the remaining admin_bar_menu registration for the deleted remove_adminbar_nodes method, preventing a fatal error when expert links are enabled.
* Improved: Regression checks now reject invalid action callbacks.

The regression reproduced the error before the fix. The full WordPress integration matrix remains deferred.
