let user = window.App.user;

module.exports = {
    updateAvatar(userProfile) {
        return userProfile.id == user.id;
    },

    owns(model, prop = 'user_id') {
        return model[prop] == user.id;
    }
};
