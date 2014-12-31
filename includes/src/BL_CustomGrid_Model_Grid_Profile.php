<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   BL
 * @package    BL_CustomGrid
 * @copyright  Copyright (c) 2014 Benoît Leulliette <benoit.leulliette@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class BL_CustomGrid_Model_Grid_Profile extends BL_CustomGrid_Object
{
    /**
     * Session base keys
     */
    const SESSION_BASE_KEY_SESSION_VALUES = '_blcg_profile_session_values_';
    const SESSION_BASE_KEY_APPLIED_FILTERS = '_blcg_applied_filters_';
    const SESSION_BASE_KEY_REMOVED_FILTERS = '_blcg_removed_filters_';
    
    /**
     * Return base helper
     *
     * @return BL_CustomGrid_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('customgrid');
    }
    
    /**
     * Return the ID of this profile
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->_getData('profile_id');
    }
    
    /**
     * Return the grid model corresponding to this profile
     *
     * @param bool $graceful Whether to throw an exception if the grid model is invalid, otherwise return null
     * @return BL_CustomGrid_Model_Grid|null
     */
    public function getGridModel($graceful = false)
    {
        if (($gridModel = $this->_getData('grid_model')) instanceof BL_CustomGrid_Model_Grid) {
            return $gridModel;
        } elseif (!$graceful) {
            Mage::throwException($this->_getHelper()->__('Invalid grid model'));
        }
        return null;
    }
    
    /**
     * Return the name of this profile
     * 
     * @return string
     */
    public function getName()
    {
        return ($this->isBase() ? $this->_getHelper()->__('Base') : $this->_getData('name'));
    }
    
    /**
     * Return whether this is the base profile from the corresponding grid model
     *
     * @return bool
     */
    public function isBase()
    {
        return ($this->getId() === $this->getGridModel()->getBaseProfileId());
    }
    
    /**
     * Return whether this is the global default profile from the corresponding grid model
     * 
     * @return bool
     */
    public function isGlobalDefault()
    {
        return ($this->getId() === $this->getGridModel()->getGlobalDefaultProfileId());
    }
    
    /**
     * Return whether this is the current profile from the corresponding grid model
     *
     * @return bool
     */
    public function isCurrent()
    {
        return ($this->getId() === $this->getGridModel()->getProfileId());
    }
    
    /**
     * Check the "Access all profiles" from the grid model
     * 
     * @return bool
     */
    protected function _checkAccessAllPermission()
    {
        return $this->getGridModel()->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_ACCESS_ALL_PROFILES);
    }
    
    /**
     * Return whether this profile is available for the current user
     * Note that this does not take edge cases into account
     * (eg, when the base profile is automatically chosen for the current user because he has not any availabel profile)
     * 
     * @param bool $checkAccessAllPermission Whether the "Access all profiles" from the grid model should also be used
     * @return bool
     */
    public function isAvailable($checkAccessAllPermission = false)
    {
        return !$this->isRestricted()
            || ($checkAccessAllPermission && $this->_checkAccessAllPermission())
            || in_array($this->getId(), $this->getGridModel()->getRoleAssignedProfilesIds(), true);
    }
    
    /**
     * Return the session parameters that should be restored upon returning to this profile,
     * if it has been previously used during the same session
     * 
     * @return string[]
     */
    public function getRememberedSessionParams()
    {
        return is_null($value = $this->_getData('remembered_session_params'))
            ? $this->getGridModel()->getProfilesRememberedSessionParams()
            : explode(',', $value);
    }
    
    /**
     * Return a full session data key, unique to this profile
     * 
     * @param string $baseKey Base session key
     * @return string
     */
    protected function _getSessionDataKey($baseKey)
    {
        return $baseKey . $this->getGridModel()->getId() . '_' . $this->getId();
    }
    
    /**
     * Return array data from the adminhtml session, corresponding to the given base key
     * 
     * @param string $sessionBaseKey Base key
     * @return array
     */
    protected function _getSessionArrayData($sessionBaseKey)
    {
        $data = $this->getGridModel()
            ->getAdminhtmlSession()
            ->getData($this->_getSessionDataKey($sessionBaseKey));
        return (is_array($data) ? $data : array());
    }
    
    /**
     * Set array data in the adminhtml session, for the given base key
     * 
     * @param string $sessionBaseKey Base key
     * @return this
     */
    protected function _setSessionArrayData($sessionBaseKey, array $data)
    {
        $this->getGridModel()
            ->getAdminhtmlSession()
            ->setData($this->_getSessionDataKey($sessionBaseKey), $data);
        return $this;
    }
    
    /**
     * Among the given session values, return the actual values that are/should be remembered
     * 
     * @param array $sessionValues Session values
     * @return array
     */
    protected function _getRememberedSessionValues(array $sessionValues)
    {
        return array_intersect_key(
            $sessionValues,
            array_flip($this->getRememberedSessionParams())
        );
    }
    
    /**
     * Get the previously remembered session values
     * 
     * @return array
     */
    public function getRememberedSessionValues()
    {
        return $this->_getRememberedSessionValues($this->_getSessionArrayData(self::SESSION_BASE_KEY_SESSION_VALUES));
    }
    
    /**
     * Set the new remembered session values
     * 
     * @return this
     */
    public function setRememberedSessionValues(array $sessionValues)
    {
        return $this->_setSessionArrayData(self::SESSION_BASE_KEY_SESSION_VALUES, $sessionValues);
    }
    
    /**
     * Return the previously applied filters for this profile from session
     * 
     * @return array
     */
    public function getSessionAppliedFilters()
    {
        return $this->_getSessionArrayData(self::SESSION_BASE_KEY_APPLIED_FILTERS);
    }
    
    /**
     * Set the new applied filters for this profile in session
     * 
     * @param array $filters New applied filters
     * @return this
     */
    public function setSessionAppliedFilters(array $filters)
    {
        return $this->_setSessionArrayData(self::SESSION_BASE_KEY_APPLIED_FILTERS, $filters);
    }
    
    /**
     * Return the previously removed filters for this profile from session
     * 
     * @return array
     */
    public function getSessionRemovedFilters()
    {
        return $this->_getSessionArrayData(self::SESSION_BASE_KEY_REMOVED_FILTERS);
    }
    
    /**
     * Set the new removed filters for this profile in session
     * 
     * @param array $filters New removed filters
     * @return this
     */
    public function setSessionRemovedFilters(array $filters)
    {
        return $this->_setSessionArrayData(self::SESSION_BASE_KEY_REMOVED_FILTERS, $filters);
    }
    
    /**
     * Return the roles IDs to which this profile is assigned
     *
     * @return int[]
     */
    public function getAssignedToRoleIds()
    {
        if (!$this->hasData('assigned_to_role_ids')) {
            $rolesIds = array();
            $rolesConfig = $this->getGridModel()->getRolesConfig();
            
            foreach ($rolesConfig as $roleId => $roleConfig) {
                if (is_array($assignedProfilesIds = $roleConfig->getData('assigned_profiles_ids'))
                    && in_array($this->getId(), $assignedProfilesIds, true)) {
                    $rolesIds[] = $roleId;
                }
            }
            
            $this->setData('assigned_to_role_ids', $rolesIds);
        }
        return $this->_getData('assigned_to_role_ids');
    }
    
    /**
     * Check, complete and return the given array of user IDs for which this profile will be set as default
     *
     * @param int[] $users User IDs
     * @return int[]
     */
    protected function _getDefaultForUsers(array $users)
    {
        $profileId = $this->getId();
        $gridModel = $this->getGridModel();
        
        $defaultForUsers = array();
        $users = array_filter($users);
        $ownUserId = $gridModel->getSessionUser()->getId();
        $ownChosen = in_array($ownUserId, $users);
        $otherChosenIds = array_diff($users, array($ownUserId));
            
        if ($gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_CHOOSE_OWN_USER_DEFAULT_PROFILE)) {
            if ($ownChosen) {
                $defaultForUsers[] = $ownUserId;
            }
        } elseif ($ownChosen) {
            $gridModel->throwPermissionException();
        } elseif ($gridModel->getUserDefaultProfileId() === $profileId) {
            $defaultForUsers[] = $ownUserId;
        }
        
        if ($gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_CHOOSE_OTHER_USERS_DEFAULT_PROFILE)) {
            $defaultForUsers = array_merge($defaultForUsers, $otherChosenIds);
        } elseif (!empty($otherChosenIds)) {
            $gridModel->throwPermissionException();
        } else {
            $usersIds = Mage::getModel('admin/user')
                ->getCollection()
                ->getAllIds();
            
            foreach ($usersIds as $userId) {
                if (($userId != $ownUserId) && ($gridModel->getUserDefaultProfileId($userId) === $profileId)) {
                    $defaultForUsers[] = $userId;
                }
            }
        }
        
        return $defaultForUsers;
    }
    
    /**
     * Check, complete and return the given array of role IDs for which this profile will be set as default
     *
     * @param int[] $roles Role IDs
     * @return int[]
     */
    protected function _getDefaultForRoles(array $roles)
    {
        $profileId = $this->getId();
        $gridModel = $this->getGridModel();
        
        $roles = array_filter($roles);
        $defaultForRoles = array();
        $ownRoleId = $gridModel->getSessionRole()->getId();
        $ownChosen = in_array($ownRoleId, $roles);
        $otherChosenIds = array_diff($roles, array($ownRoleId));
            
        if ($gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_CHOOSE_OWN_ROLE_DEFAULT_PROFILE)) {
            if ($ownChosen) {
                $defaultForRoles[] = $ownRoleId;
            }
        } elseif ($ownChosen) {
            $gridModel->throwPermissionException();
        } elseif ($gridModel->getRoleDefaultProfileId() === $profileId) {
            $defaultForRoles[] = $ownRoleId;
        }
        if ($gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_CHOOSE_OTHER_ROLES_DEFAULT_PROFILE)) {
            $defaultForRoles = array_merge($defaultForRoles, $otherChosenIds);
        } elseif (!empty($otherChosenIds)) {
            $gridModel->throwPermissionException();
        } else {
            $rolesIds = Mage::getModel('admin/roles')
                ->getCollection()
                ->getAllIds();
            
            foreach ($rolesIds as $roleId) {
                if (($roleId != $ownRoleId) && ($gridModel->getRoleDefaultProfileId($roleId) === $profileId)) {
                    $defaultForRoles[] = $roleId;
                }
            }
        }
        
        return $defaultForRoles;
    }
    
    /**
     * (Un-)Choose this profile as default for given users and roles, and globally
     * (expected values and corresponding possibilities depending on permissions)
     *
     * @param array $values Array with "users", "roles" and "global" keys, holding corresponding value(s)
     * @return this
     */
    public function chooseAsDefault(array $values)
    {
        $profileId  = $this->getId();
        $gridModel  = $this->getGridModel();
        $defaultFor = array();
        
        if (!$this->isAvailable(true)) {
            Mage::throwException($this->_getHelper()->__('This profile is not available'));
        }
        if (isset($values['users']) && is_array($values['users'])) {
            $defaultFor['users'] = $this->_getDefaultForUsers($values['users']);
        }
        if (isset($values['roles']) && is_array($values['roles'])) {
            $defaultFor['roles'] = $this->_getDefaultForRoles($values['roles']);
        }
        if (isset($values['global'])) {
            if ($gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_CHOOSE_GLOBAL_DEFAULT_PROFILE)) {
                $defaultFor['global'] = (bool) $values['global'];
            } else {
                $gridModel->throwPermissionException();
            }
        }
        
        $gridModel->getResource()->chooseProfileAsDefault($gridModel->getId(), $profileId, $defaultFor);
        $gridModel->resetUsersConfigValues();
        $gridModel->resetRolesConfigValues();
        $gridModel->resetProfilesValues();
        
        return $this;
    }
    
    /**
     * Check whether the given profile values would result in a duplicated profile,
     * throw a corresponding exception if this is the case
     * 
     * @param int|null $checkedProfileId Checked profile ID (may be null in case of a new profile)
     * @param array $checkedProfileValues Checked profile values
     * @param BL_CustomGrid_Model_Grid_Profile[] $profiles List of all other profiles to check against
     * @return this
     */
    protected function _checkProfileDuplication($checkedProfileId, array $checkedProfileValues, array $profiles)
    {
        foreach ($profiles as $profile) {
            if ((trim($profile->getName()) === $checkedProfileValues['name'])
                && (is_null($checkedProfileId) || ($profile->getId() !== $checkedProfileId))) {
                Mage::throwException(
                    $this->_getHelper()->__('Another profile from the same grid already has this name')
                );
            }
        }
        return $this;
    }
    
    /**
     * Check the validity of the given new values for the given profile ID, adapt them if necessary
     * 
     * @param int|null $profileId Updated profile ID
     * @param array $values New profile values
     * @return this
     */
    protected function _checkProfileNewValues($profileId, array &$values)
    {
        $gridModel = $this->getGridModel();
        
        if (is_null($profileId) || ($profileId !== $gridModel->getBaseProfileId())) {
            if (!isset($values['name']) || !strlen(trim($values['name']))) {
                Mage::throwException($this->_getHelper()->__('The profile name must be filled'));
            }
            
            $values['name'] = trim($values['name']);
            $this->_checkProfileDuplication($profileId, $values, $this->getGridModel()->getProfiles());
        } elseif (isset($values['name'])) {
            unset($values['name']);
        }
        
        return $this;
    }
    
    /**
     * Return whether the given values correspond to a restricted profile
     * 
     * @param array $values Profile values
     * @return bool
     */
    protected function _isRestrictedProfileValues(array $values)
    {
        return (isset($values['is_restricted']) && $values['is_restricted'])
            && (isset($values['assigned_to']) && is_array($values['assigned_to']) && !empty($values['assigned_to']));
    }
    
    /**
     * Copy this profile to a new one, and return the new profile ID
     *
     * @param array $newValues New profile values
     * @return int New profile ID
     */
    public function copyToNew(array $values)
    {
        $profileId = $this->getId();
        $gridModel = $this->getGridModel();
        
        if (!$gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_COPY_PROFILES_TO_NEW)) {
            $gridModel->throwPermissionException();
        } elseif (!$this->isAvailable(true)) {
            Mage::throwException($this->_getHelper()->__('The copied profile is not available'));
        }
        
        $this->_checkProfileNewValues(null, $values);
        $assignedRolesIds = null;
        
        if ($gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_ASSIGN_PROFILES)) {
            if ($this->_isRestrictedProfileValues($values)) {
                $assignedRolesIds = array_unique($values['assigned_to']);
            }
        } elseif ($gridModel->getProfilesDefaultRestricted()) {
            $assignedRolesIds = $gridModel->getProfilesDefaultAssignedTo();
            $creatorRoleIdKey = array_search(
                BL_CustomGrid_Model_System_Config_Source_Admin_Role::CREATOR_ROLE,
                $assignedRolesIds
            );
            
            if ($creatorRoleIdKey !== false) {
                unset($assignedRolesIds[$creatorRoleIdKey]);
                $assignedRolesIds[] = $gridModel->getSessionRole()->getId();
                $assignedRolesIds = array_unique($assignedRolesIds);
            }
        }
        
        $values['is_restricted'] = !is_null($assignedRolesIds);
        $values['assigned_to'] = $assignedRolesIds;
        
        $newProfileId = $gridModel->getResource()->copyProfileToNew($gridModel->getId(), $profileId, $values);
        $gridModel->resetProfilesValues();
        
        if ($values['is_restricted']) {
            $gridModel->resetRolesConfigValues();
        }
        
        return (int) $newProfileId;
    }
    
    /**
     * Copy some of the values from this profile to another existing profile
     *
     * @param int $toProfileId ID of the profile on which to copy the given values
     * @param array $values Copied values (possible values : "columns", and each grid parameter key - eg "page")
     * @return this
     */
    public function copyToExisting($toProfileId, array $values)
    {
        $profileId = $this->getId();
        $gridModel = $this->getGridModel();
        $profiles  = $gridModel->getProfiles(true);
        
        if (!$gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_COPY_PROFILES_TO_EXISTING)) {
            $gridModel->throwPermissionException();
        } elseif (!isset($profiles[$profileId])) {
            Mage::throwException($this->_getHelper()->__('The copied profile is not available'));
        } elseif (!isset($profiles[$toProfileId])) {
            Mage::throwException($this->_getHelper()->__('The profile on which to copy is not available'));
        } elseif ($profileId === $toProfileId) {
            Mage::throwException($this->_getHelper()->__('A profile can not be copied to itself'));
        }
        
        $gridModel->getResource()->copyProfileToExisting($gridModel->getId(), $profileId, $toProfileId, $values);
        $gridModel->resetProfilesValues();
        
        return $this;
    }
    
    /**
     * Update base values
     *
     * @param array $values New values
     * @return this
     */
    public function update(array $values)
    {
        $profileId = $this->getId();
        $gridModel = $this->getGridModel();
        
        if (!$gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_EDIT_PROFILES)) {
            $gridModel->throwPermissionException();
        } elseif (!$this->isAvailable(true)) {
            Mage::throwException($this->_getHelper()->__('This profile is not available'));
        }
        
        $editableKeys = array('name', 'remembered_session_params');
        $values = array_intersect_key($values, array_flip($editableKeys));
        $this->_checkProfileNewValues($profileId, $values);
        
        if (!isset($values['remembered_session_params']) || is_array($values['remembered_session_params'])) {
            $sessionParams = array_intersect($values['remembered_session_params'], $gridModel->getGridParamsKeys(true));
            
            if (in_array(BL_CustomGrid_Model_Grid::GRID_PARAM_NONE, $sessionParams)) {
                $sessionParams = array(BL_CustomGrid_Model_Grid::GRID_PARAM_NONE);
            }
            
            $values['remembered_session_params'] = (empty($sessionParams) ? null : implode(',', $sessionParams));
        } else {
            $values['remembered_session_params'] = null;
        }
        
        $gridModel->getResource()->updateProfile($gridModel->getId(), $profileId, $values, !$this->getIsBulkSaveMode());
        $this->addData($values);
        
        return $this;
    }
    
    /**
     * Prepare the given default page value
     * 
     * @param string $value Default page value
     * @return int
     */
    protected function _prepareDefaultPageValue($value)
    {
        return (int) $value;
    }
    
    /**
     * Prepare the given default limit value
     * 
     * @param string $value Default limit value
     * @return int
     */
    protected function _prepareDefaultLimitValue($value)
    {
        return (int) $value;
    }
    
    /**
     * Prepare the given default sort value
     * 
     * @param string $value Default sort value
     * @return string|null
     */
    protected function _prepareDefaultSortValue($value)
    {
        return $this->getGridModel()->getColumnByBlockId($value)
            ? $value
            : null;
    }
    
    /**
     * Prepare the given default direction value
     * 
     * @param string $value Default direction value
     * @return string|null
     */
    protected function _prepareDefaultDirValue($value)
    {
        $value = strtolower($value);
        return (in_array($value, array('asc', 'desc')) ? $value : null);
    }
    
    /**
     * Prepare the given default filter value
     * 
     * @param string $value Default filter value
     * @return string|null
     */
    protected function _prepareDefaultFilterValue($value)
    {
        return $this->getGridModel()
            ->getApplier()
            ->prepareDefaultFilterValue($value);
    }
    
    /**
     * Extract the request value corresponding to the given default parameter keys,
     * from the given appliable and removable parameter values
     * 
     * @param string $paramKey Key of the default parameter (eg "page" or "limit")
     * @param string $valueKey Value key of the default parameter (eg "default_page" or "default_limit")
     * @param array $appliable Appliable default values
     * @param array $removable Removable default values
     * @return mixed
     */
    protected function _extractDefaultParamValue($paramKey, $valueKey, array $appliable, array $removable)
    {
        if (isset($removable[$paramKey]) && $removable[$paramKey]) {
            $value = null;
        } elseif (isset($appliable[$paramKey])) {
            $value = $this->{'_prepareDefault' . ucfirst($paramKey) . 'Value'}($appliable[$paramKey]);
        } else {
            $value = $this->_getData($valueKey);
        }
        return $value;
    }
    
    /**
     * Update default parameters
     *
     * @param array $appliable Appliable values
     * @param array $removable Removable values
     * @return this
     */
    public function updateDefaultParameters(array $appliable, array $removable)
    {
        $gridModel = $this->getGridModel();
        
        if (!$gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_EDIT_DEFAULT_PARAMS)) {
            $gridModel->throwPermissionException();
        }
        
        $defaultParams = array();
        
        foreach ($gridModel->getGridParamsKeys() as $paramKey) {
            $valueKey = 'default_' . $paramKey;
            $defaultParams[$valueKey] = $this->_extractDefaultParamValue($paramKey, $valueKey, $appliable, $removable);
        }
        
        $gridModel->getResource()->updateProfile($gridModel->getId(), $this->getId(), $defaultParams, true);
        $this->addData($defaultParams);
        
        return $this;
    }
    
    /**
     * (Un-)Restrict and/or (un-)assign this profile
     *
     * @param array $values Array with "is_restricted" and "assigned_to" keys, holding corresponding value(s)
     * @return this
     */
    public function assign(array $values)
    {
        $profileId = $this->getId();
        $gridModel = $this->getGridModel();
        $profiles  = $gridModel->getProfiles(true);
        
        if (!$gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_ASSIGN_PROFILES)) {
            $gridModel->throwPermissionException();
        } elseif (!isset($profiles[$profileId])) {
            Mage::throwException($this->_getHelper()->__('This profile is not available'));
        }
        
        $editableKeys = array('is_restricted', 'assigned_to');
        $values = array_intersect_key($values, array_flip($editableKeys));
        
        if ($this->_isRestrictedProfileValues($values)) {
            $values['is_restricted'] = true;
        } else {
            $values['is_restricted'] = false;
            $values['assigned_to'] = null;
        }
        
        $gridModel->getResource()->updateProfile($gridModel->getId(), $profileId, $values, !$this->getIsBulkSaveMode());
        $gridModel->resetProfilesValues();
        $gridModel->resetRolesConfigValues();
        $this->unsetData('assigned_to_role_ids');
        
        return $this;
    }
    
    /**
     * Delete this profile
     *
     * @return this
     */
    public function delete()
    {
        $profileId = $this->getId();
        $gridModel = $this->getGridModel();
        
        if (!$gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_DELETE_PROFILES)) {
            $gridModel->throwPermissionException();
        } elseif (!$this->isAvailable(true)) {
            Mage::throwException($this->_getHelper()->__('This profile is not available'));
        } elseif ($this->isBase()) {
            Mage::throwException($this->_getHelper()->__('The base profile can not be deleted'));
        }
        
        $gridModel->getResource()->deleteProfile($gridModel->getId(), $profileId);
        $gridModel->resetProfilesValues();
        $gridModel->resetUsersConfigValues();
        $gridModel->resetRolesConfigValues();
        
        return $this;
    }
}
