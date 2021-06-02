import matplotlib.pyplot as plt
import numpy as np
import pandas
import statistics
import seaborn as sns
from sklearn import metrics
from sklearn.ensemble import GradientBoostingRegressor
from sklearn.ensemble import RandomForestRegressor
from sklearn.ensemble import VotingRegressor
from sklearn.linear_model import LinearRegression
from sklearn.model_selection import GridSearchCV
from sklearn.model_selection import train_test_split
from sklearn.neural_network import MLPRegressor


def evaluate(model, test_features, test_labels, verbose=1):
    """
    fonction d'évaluation de model de prediction
    :param model: le model de prédiction à évaluer
    :param test_features: set de donnée pour les prédiction
    :param test_labels: set de la valeur de comparaison de la prediction
    :param verbose: permet si = 1 d'afficher la moyenne d'erreur ainsi que la précision
    :return: la presicion (accuracy) 1 - abs(predictions - test_labels)/test_labels
    """
    predictions = model.predict(test_features)
    errors = abs(predictions - test_labels)
    mape = 100 * np.mean(errors / test_labels)
    accuracy = 100 - mape
    if verbose == 1:
        print('Model Performance')
        print('Average Error: {:0.4f} degrees.'.format(np.mean(errors)))
        print('Accuracy = {:0.2f}%.'.format(accuracy))
    return accuracy


def minmax_norm(df_input):
    """
    fonction normalisant la database donnée en entrée. Elle normalise tout les valeurs
    :param df_input: database à normaliser
    :return: database normalisé
    """
    return (df_input - df_input.min()) / (df_input.max() - df_input.min())


def result(pred, Rvalue):
    """
    fonction renvoyant un dictionnaire comportant tout les metrics possible sur des predictions
    :param pred: tableau de prédiction de valeur
    :param Rvalue: tableau des valeurs réel à comparé à celle predite
    :return: dictionnaire comportant des metrics
    """
    return {"Explained varince score": metrics.explained_variance_score(Rvalue, pred),
            "Max error": metrics.max_error(Rvalue, pred),
            "Mean Absolute Error": metrics.mean_absolute_error(Rvalue, pred),
            "Mean Squared Error": metrics.mean_squared_error(Rvalue, pred),
            "mean squared log error": metrics.mean_squared_log_error(Rvalue, pred),
            "median absolute error": metrics.median_absolute_error(Rvalue, pred),
            "r2 score": metrics.r2_score(Rvalue, pred),
            "mean poisson devianc": metrics.mean_poisson_deviance(Rvalue, pred),
            "mean gamma deviance": metrics.mean_gamma_deviance(Rvalue, pred),
            "mean absolute percentage error": metrics.mean_absolute_percentage_error(Rvalue, pred),
            "Root Mean Squared Error": np.sqrt(metrics.mean_squared_error(Rvalue, pred))}


def displayResult(dic, label=""):
    """
    fonction affichant un dictionnaire de maniere simple en spécifiant un titre
    :param dic: dictionnaire à afficher
    :param label: titre du dictionnaire
    """
    print("\n############### RESULT {} ###############".format(label))
    for key, value in dic.items():
        print(" - {} : {}".format(key, value[:3]))


def explainResult(dic, exclude=[], desc=[]):
    """
    fonction classant les differents algorithmes en fonctions de leur score dans les metrics du dictionnaire
    :param dic: dictionnaire à classer
    :param exclude: liste des elements à ne pas inclure dans le classement
    :param desc: liste des element à classer de maniere decroissante (score)
    :return: dictionnaire avec les algorithmes classé du meilleur au moins bon dans chaque metrics
    """
    res = {"Explained varince score": [],
           "Max error": [],
           "Mean Absolute Error": [],
           "Mean Squared Error": [],
           "mean squared log error": [],
           "median absolute error": [],
           "r2 score": [],
           "mean poisson devianc": [],
           "mean gamma deviance": [],
           "mean absolute percentage error": [],
           "Root Mean Squared Error": []}
    for key, value in dic.items():
        if key not in exclude:
            for k, v in res.items():
                res[k].append((key, value[k]))
    for k, v in res.items():
        if k in desc:
            res[k] = sorted(v, key=lambda val: val[1], reverse=True)
        else:
            res[k] = sorted(v, key=lambda val: val[1])
    return res


def toDicByFactor(pred, facteur):
    """
    fonction renvoyant un dictionnaire de type {X:[,]}. cette fonction va classer par facteur les predictions. Chaque element index I
    dans le tableau pred appartient à la classe indiqué à la position I du tableau facteur. Ainsi on va regroupé toute les données de
    même classe dans un dictionnaire repertoriant chaque classe ainsi que les valeurs de cette classe.

    :param pred: tableau à classifier
    :param facteur: correspondance de classe des elements du tableau pred
    :return: dictionnaire classifier contenant tout les elements differents du tableau facteur
    """
    if len(pred) != len(facteur):
        raise Exception("les tableaux ne sont pas de même logueur")
    else:
        res = {}
        for i in range(len(pred)):
            if facteur[i] not in res.keys():
                res[facteur[i]] = [pred[i], ]
            else:
                res[facteur[i]].append(pred[i])
        res2 = dict(sorted(res.items(), key=lambda t: t[0]))
    return res2


def correctPred(pred):
    """
    fonction remplacant tout les resultats egal à zero par un float > 0 mais tres petit, ici 0.00000000001
    :param pred: tableau à une dimension
    :return: tableau à une dimension avec que des valeurs superieur à 0
    """
    res = []
    for i in range(len(pred)):
        if pred[i] <= 0:
            res.append(0.00000000001)
        else:
            res.append(pred[i])
    return res


# TODO: fonction d'export des results
def toExport(data):
    return None


def inputValue(pathFile, varToSet=[], exclude=[], unNormalized=[], verbose=False, min=[], max=[], dblCrit=[]):
    """
    fonction permettant de créer un dataset panda et de le retourné normalisé en suivant un certain nombre de parametre.

    :param pathfile: chemin vers le csv contenant toute les données
    :param varToSet: les colonnes auquel apporté des modification
    :param exclude: les colonnes a supprimer
    :param verbose: bollean permettant l'affichage de donnée sur le tableau de base
    :param min: selection numérique minimal associer à varToSet avec l'index comme moyen de liaison
    :param max: selection numérique maximal associer à varToSet avec l'index comme moyen de liaison
    :param dblCrit: selectionne les lignes colonne sur lequel chercher les valeur identique.
    :return: renvoie le dataset comme paramettré et un tableau Yn (index des val du premier tableau)
    """
    if len(varToSet) != len(min) or len(varToSet) != len(max):
        raise Exception("les tableaux ne sont pas de même logueur")
    heures_data = pandas.read_table(pathFile, sep=",", header=0, decimal=".")
    del heures_data["ENERGIA2"]
    if verbose:
        print(heures_data.columns)
        print(heures_data.shape)
        print(heures_data.head())
        print(heures_data.describe().transpose())
    X = heures_data.drop(exclude, axis=1)
    shapeB = X.shape
    X = X.drop_duplicates(subset=dblCrit, keep="first")
    print("sppr duplicate value from {} to {} ".format(shapeB, X.shape))
    for i in range(len(varToSet)):
        X = X[X[varToSet[i]] > min[i]]
        X = X[X[varToSet[i]] < max[i]]
    if len(X.columns) == len(unNormalized):
        Xn = X
        Yn = [i for i in range(Xn.shape[0])]
    else:
        Xn = minmax_norm(X)
        Yn = [i for i in range(Xn.shape[0])]
        for element in unNormalized:
            Xn = Xn.drop(columns=element)
            Xn[element] = X[element]
    return Xn, Yn


def AlgoComparator(X_normal, Y_normal, test_s=0.2):
    ZTrain, ZTest, y_train, y_test = train_test_split(X_normal, Y_normal, test_size=test_s)

    # choix des variables à partir de ZTrain
    YTrain = ZTrain.drop(["ENERGIA1"], axis=1)
    YVar = ZTrain["ENERGIA1"]
    YTest = ZTest.drop(["ENERGIA1"], axis=1)
    YTRVar = ZTest["ENERGIA1"]

    #
    # INIT REGRESSOR
    #

    # ANN
    reg0 = MLPRegressor(hidden_layer_sizes=(13, 13, 13), random_state=1, max_iter=1500)
    reg0.fit(YTrain, YVar)

    # RANDOM FOREST
    reg1 = RandomForestRegressor(bootstrap=True, max_depth=6, max_features=0.65, min_samples_leaf=6, min_samples_split=2, n_estimators=1450, random_state=5)
    reg1.fit(YTrain, YVar)

    # GRADIENT BOOSTING REGRESSOR
    reg2 = GradientBoostingRegressor(random_state=200)
    reg2.fit(YTrain, YVar)

    # LINEAR REGRESSOR
    reg3 = LinearRegression()
    reg3.fit(YTrain, YVar)

    # VotingRegressor
    ereg = VotingRegressor([('rf', reg1), ('gb', reg2), ('lr', reg3)])
    ereg.fit(YTrain, YVar)

    #
    #    TIME FOR PREDICTION
    #
    pred0 = correctPred(reg0.predict(YTest))
    pred1 = correctPred(reg1.predict(YTest))
    pred2 = correctPred(reg2.predict(YTest))
    pred3 = correctPred(reg3.predict(YTest))
    pred4 = correctPred(ereg.predict(YTest))
    zr = []
    zh = []
    for i in range(len(pred0)):
        zr.append(YTRVar.iloc[i])
        zh.append(ZTest["HOUR"].iloc[i])

    ###### RESULT ######
    res = {
        "MultiLayerPerceptronRegressor": result(pred0, zr),
        "RandomForestRegressor": result(pred1, zr),
        "GradientBoostingRegressor": result(pred2, zr),
        "LinearRegressor": result(pred3, zr),
        "VotingRegressor with RFR, GBR, LR": result(pred4, zr)
    }
    er = explainResult(res, desc=["Explained varince score", "r2 score"])
    displayResult(er)

    ###### START PLOT #####

    plt.figure()
    plt.plot(zh, pred1, 'b^', label='RandomForestRegressor')
    plt.plot(zh, pred2, 'gd', label='GradientBoostingRegressor')
    plt.plot(zh, pred3, 'ys', label='LinearRegression')
    plt.plot(zh, pred4, 'r*', ms=10, label='VotingRegressor')
    plt.plot(zh, zr, 'rx', label='RealValue')

    # plt.tick_params(axis='x', which='both', bottom=False, top=False,
    #                labelbottom=False)
    plt.ylabel('predicted')
    plt.xlabel('normalized hour')
    plt.legend(loc="best")
    plt.title('Regressor predictions and their average')

    plt.show()
    ######  END PLOT  #####
    ###### START TEST BAM PLOT #########

    predA = toDicByFactor(pred0, zh)
    predM = toDicByFactor(pred1, zh)
    RV = toDicByFactor(zr, zh)

    #### START MEANS ####
    i = 0
    RVM = []
    predMM = []
    predAM = []
    y = []
    for key, value in RV.items():
        RVM.append(statistics.mean(value))
    for key, value in predM.items():
        predMM.append(statistics.mean(value))
    for key, value in predA.items():
        predAM.append(statistics.mean(value))
    for key, value in RV.items():
        y.append(key)
    y.sort()
    del y[0]
    del RVM[0]
    del predMM[0]
    del predAM[0]
    print(RVM)
    print(predMM)

    plt.figure()
    plt.plot(y, RVM, label='real value')
    plt.plot(y, predMM, label='RandomForestRegressor prediction')
    plt.plot(y, predAM, label='Multi-layer perceptron prediction')
    plt.ylabel('energie production')
    plt.xlabel('hour')
    plt.legend(loc="best")
    plt.title('Regressor predictions and the real production')

    plt.show()
    #### END MEANS ####

    """
    plt.figure(1)
    plt.subplot(2,1,2)
    plt.boxplot([predM[x] for x in predM.keys()], labels=predM.keys())
    plt.subplot(1, 2, 1)
    plt.boxplot([RV[x] for x in RV.keys()], labels=predM.keys())
    plt.xlabel('normalized hour')
    plt.ylabel('predicted')
    plt.legend(loc="best")
    plt.title('RandomForestRegressor predictions and their average')
    plt.show()
    
    plt.figure(2, figsize=(25, 12))
    # plt.gcf().subplots_adjust(left=0.2, bottom=0.2, right=1.5,
    #                          top=0.9, wspace=0, hspace=0.5)
    # division de la fenêtre graphique en 3 lignes, 1 colonne,
    # graphique en position 1
    # puis caractéristiques de ce graphique

    plt.subplot(2, 1, 1)
    plt.boxplot([predM[x] for x in predM.keys()], labels=predM.keys())
    plt.title('RandomForestRegressor predictions and their average')
    plt.grid()
    plt.xlabel('normalized hour')
    plt.ylabel('predicted')
    plt.ylim(0)

    # division de la fenêtre graphique en 3 lignes, 1 colonne,
    # graphique en position 2
    # puis caractéristiques de ce graphique

    plt.subplot(2, 1, 2)
    plt.boxplot([RV[x] for x in RV.keys()], labels=predM.keys())
    plt.title('RealValue predictions and their average')
    plt.grid()
    plt.xlabel('normalized hour')
    plt.ylabel('predicted')

    plt.show()

    ######  END TEST BAM PLOT  #########

    
    ZTETT = ZTest
    # print(predm_sklearn)
    # print(pmc_sklearn.score(ZTest, ZTETT[3]))
    # eval_model(ZTETT[3], predm_sklearn, X_test.T)
    #print(regressor.score(ZTest.drop(["ENERGIA1"], axis=1), ZTest["ENERGIA1"]))

    plt.figure()
    plt.rcParams["figure.figsize"] = (1, 100)
    plt.gca().xaxis.set_ticks(range(0, 10000, 10), minor=True)
    plt.plot([e for e in range(len(pred1))], pred1, "x", label="prevision")
    plt.plot([e for e in range(len(ZTETT["ENERGIA1"]))], ZTETT["ENERGIA1"], "x", label="original")
    plt.legend()

    plt.savefig('test.png', dpi=300)

    #
    # SAVE TO CSV
    #
    pred = pandas.Series(pred1, name="prediction", index=YTRVar)
    pred.to_csv("JSP.csv")
    
    norm = ZTETT.rename(
        columns={'DAY': 'd_n', 'HOUR': 'h_n', 'ENERGIA1': 'e_n', 'TEMPERATURA': 't_n', 'UMIDADE': 'u_n', 'CHUVA': 'c_n',
                 'IRRADIACAO': 'i_n'})
    newTab = pandas.concat([norm, pred], axis=1)
    newTab.to_csv("testPred2.csv")
    allTab = pandas.concat([newTab, X], axis=1, join="inner")
    allTab.to_csv("testPredAll.csv")
    """


def optiRegressor(regressor, gridParam, pYTrain, pYVar, verbose=1):
    """
    optiRegressor permet de tester tout les regressors possiblement faisable avec les la grille de parametre
    :param regressor: le model à tester
    :param gridParam: les parametres à tester sur le regressor
    :param pYTrain: les données uttilisé pour la prediction
    :param pYVar: la valeur associé à la prédiction (valeur à predire)
    :param verbose: permet de gerer le niveau d'écriture de la fonction GridSearchCV (par default = 1)
    :return: un objet {best_params, best_grid, grid_search}
    """
    # Instantiate the grid search model
    grid_search = GridSearchCV(estimator=regressor, param_grid=gridParam,
                               cv=3, n_jobs=-1, verbose=verbose)
    # Fit the grid search to the data
    grid_search.fit(pYTrain, pYVar)
    return {
        "best_params": grid_search.best_params_,
        "best_grid": grid_search.best_estimator_,
        "grid_search": grid_search
    }


def printTestResult(grid_res):
    """
    premettant l'affichage des resultats des fonction de test
    :param grid_res: dictionaire contient differents element affiché par la suite
    :return: None ( void function)
    """
    print(grid_res["best_params"])
    print(grid_res["best_grid"])
    print('Improvement of {:0.2f}%.'.format(
        100 * (grid_res["grid_accuracy"] - grid_res["base_accuracy"]) / grid_res["base_accuracy"]))


def dataSetup(X_normal, Y_normal, toPredict, test_s=0.2, verbose=0):
    """
    met en forme les données à utiliser pour les models de prediction
    :param X_normal: dataset à split
    :param Y_normal: Yn (index des val de X_normal)
    :param toPredict: variable(s) faisant l'objet de la prediction. Elle est eleve
    :param test_s: taille du tableau de test
    :param verbose: permet l'affichade d'information si > 0
    :return: set d'entrainement, valeur réel à estimer du set d'entrainement, set de test, valeur réel à estimer du set de test
    """
    ZTrain, ZTest, y_train, y_test = train_test_split(X_normal, Y_normal, test_size=test_s)
    if verbose > 0:
        print(len(ZTrain))
    # choix des variables à partir de ZTrain
    return ZTrain.drop([toPredict], axis=1), ZTrain[toPredict], ZTest.drop([toPredict], axis=1), ZTest[toPredict]


def RFRTest(X_normal, Y_normal, test_s=0.2):
    # choix des variables à partir de ZTrain
    YTrain, YVar, YTest, YTRVar = dataSetup(X_normal, Y_normal, "ENERGIA1", test_s, verbose=1)

    # Create a based model
    reg1 = RandomForestRegressor(n_estimators=200, random_state=0)
    reg1.fit(YTrain, YVar)

    # Search an optimal RFR
    # Create the parameter grid based on the results of random search
    param_grid = {
        'bootstrap': [True],
        'random_state': [3, 4, 5],
        'max_depth': [6, 7, 8],
        'max_features': [0.65, 0.7, 0.75],
        'min_samples_leaf': [5, 6],
        'min_samples_split': [2],
        'n_estimators': [1450, 1475, 1500, 1525, 1550]
    }
    rf = RandomForestRegressor()
    res = optiRegressor(rf, param_grid, YTrain, YVar)

    res["grid_accuracy"] = evaluate(res["best_grid"], YTest, YTRVar)
    res["base_accuracy"] = evaluate(reg1, YTest, YTRVar)
    printTestResult(res)


def MLPRTest(X_normal, Y_normal, test_s=0.2):
    YTrain, YVar, YTest, YTRVar = dataSetup(X_normal, Y_normal, "ENERGIA1", test_s, verbose=1)

    # Create an initial model to compare
    reg1 = MLPRegressor(hidden_layer_sizes=(13, 13, 13), random_state=1)
    reg1.fit(YTrain, YVar)

    # search an optimal MLP
    # Create the parameter grid
    param_grid = {
        "hidden_layer_sizes": [(13, 13, 13), (50, 50, 50)],
        "activation": ["identity", "logistic", "tanh", "relu"],
        "solver": ["adam"],
        "alpha": [0.00005, 0.0005],
        "random_state": [1, 2],
        "max_iter": [2000]
    }
    rf = MLPRegressor()
    res = optiRegressor(rf, param_grid, YTrain, YVar)
    res["grid_accuracy"] = evaluate(res["best_grid"], YTest, YTRVar)
    res["base_accuracy"] = evaluate(reg1, YTest, YTRVar)
    printTestResult(res)


def main():
    X_n, Y_n = inputValue("./ALLvalue.csv", varToSet=["ENERGIA1"], exclude=["MONTH", "DAY", "YEAR"],
                          unNormalized=["ENERGIA1"], min=[1], max=[50], dblCrit=["ENERGIA1", "IRRADIACAO"])
    cor = X_n.corr()
    ax = sns.heatmap(cor, square=True, cmap="coolwarm", linewidths=.5, annot=True)
    plt.show()
    print(X_n.shape)
    print(X_n.describe().transpose())
    AlgoComparator(X_n, Y_n)
    # MLPRTest(X_n, Y_n)


if __name__ == '__main__':
    main()
