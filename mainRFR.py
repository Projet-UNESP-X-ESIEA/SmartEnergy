from keras.datasets import mnist
from keras.utils import np_utils
import pandas
import matplotlib.pyplot as plt
import numpy as np
from sklearn.cluster import KMeans
from sklearn.decomposition import PCA
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.ensemble import RandomForestRegressor
from sklearn.neural_network import MLPClassifier, MLPRegressor
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import classification_report, confusion_matrix, accuracy_score, f1_score
from sklearn import metrics
from sklearn.ensemble import GradientBoostingRegressor
from sklearn.ensemble import VotingRegressor


def analyseColumn(ptable, pnbX):
    rep = [0 for _ in range(pnbX)]
    print(rep)
    rep[0] = ptable.max()
    rep[1] = ptable.min()
    print(rep)
    return rep


def minmax_norm(df_input):
    return (df_input - df_input.min()) / (df_input.max() - df_input.min())


def eval_model(y_true, y_pred, x_nor):
    # matrice de confusion
    mc = confusion_matrix(y_true, y_pred)
    print("Confusion matrix :")
    print(mc)
    # taux d'erreur
    err = 1 - accuracy_score(y_true, y_pred)
    print("Err-rate = ", err)
    # F1-score
    f1 = f1_score(x_nor[3])
    print("F1-Score = ", f1)

def result(pred, Rvalue, label=""):
    print("###### RESULT {} #######".format(label))
    print('Explained varince score : ', metrics.explained_variance_score(Rvalue, pred))
    print('Max error : ', metrics.max_error(Rvalue, pred))
    print('Mean Absolute Error: ', metrics.mean_absolute_error(Rvalue, pred))
    print('Mean Squared Error: ', metrics.mean_squared_error(Rvalue, pred))
    print('mean squared log error : ', metrics.mean_squared_log_error(Rvalue, pred))
    print('median absolute error : ', metrics.median_absolute_error(Rvalue, pred))
    print('r2 score : ', metrics.r2_score(Rvalue, pred))
    print('mean poisson deviance : ', metrics.mean_poisson_deviance(Rvalue, pred))
    print('mean gamma deviance : ', metrics.mean_gamma_deviance(Rvalue, pred))
    print('mean absolute percentage error : ', metrics.mean_absolute_percentage_error(Rvalue,pred))
    print('Root Mean Squared Error: ', np.sqrt(metrics.mean_squared_error(Rvalue, pred)))


def main():
    heures_data = pandas.read_table("../poland/neural network/ALLvalue.csv", sep=",", header=0, decimal=".")
    del heures_data["ENERGIA2"]
    print(heures_data.columns)
    print(heures_data.shape)
    print(heures_data.head())
    print(heures_data.describe().transpose())

    X = heures_data.drop(["MONTH", "DAY", "YEAR"], axis=1)
    X = heures_data[X["ENERGIA1"] > 0]
    X = X[X["ENERGIA1"] < 400]

    X_normal = minmax_norm(X)
    Y_normal = [i for i in range(X_normal.shape[0])]

    #denormalisation de ENERGIA1
    X_normal = X_normal.drop(columns="ENERGIA1")
    X_normal["ENERGIA1"] = X["ENERGIA1"]

    print(X_normal.describe().transpose())
    ZTrain, ZTest, y_train, y_test = train_test_split(X_normal, Y_normal, test_size=0.2)

    print(len(ZTrain))

    #choix des variables à partir de ZTrain
    YTrain = ZTrain.drop(["ENERGIA1","HOUR"], axis=1)
    YVar = ZTrain["ENERGIA1"]
    YTest = ZTest.drop(["ENERGIA1", "HOUR"], axis=1)[:8]
    YTRVar = ZTest["ENERGIA1"]


    #
    # INIT REGRESSOR
    #

    #ANN
    reg0 = MLPRegressor(hidden_layer_sizes=(13, 13, 13), random_state=1, max_iter=1500, solver="lbfgs")
    reg0.fit(YTrain, YVar)

    #RANDOM FOREST
    reg1 = RandomForestRegressor(n_estimators=200, random_state=0)
    reg1.fit(YTrain, YVar)

    #GRADIENT BOOSTING REGRESSOR
    reg2 = GradientBoostingRegressor(random_state=200)
    reg2.fit(YTrain, YVar)

    #LINEAR REGRESSOR
    reg3 = LinearRegression()
    reg3.fit(YTrain, YVar)

    #VotingRegressor
    ereg = VotingRegressor([('rf', reg1), ('gb', reg2), ('lr', reg3)])
    ereg.fit(YTrain, YVar)

    #
    #    TIME FOR PREDICTION
    #
    pred0 = reg0.predict(YTest)
    pred1 = reg1.predict(YTest)
    pred2 = reg2.predict(YTest)
    pred3 = reg3.predict(YTest)
    pred4 = ereg.predict(YTest)
    zr = []
    zh = []
    print(pred2)
    for i in range(8):
        zr.append(YTRVar.iloc[i])
        zh.append(ZTest["HOUR"].iloc[i])


    ###### RESULT ######
    result(pred0, YTRVar, "MultiLayerPerceptronRegressor")
    result(pred1, YTRVar, "RandomForestRegressor")
    result(pred2, YTRVar, "GradientBoostingRegressor")
    result(pred3, YTRVar, "LinearRegressor")
    result(pred4, YTRVar, "VotingRegressor with RFR, GBR, LR")

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
    ##### END PLOT #####


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

    pred = pandas.Series(pred1, name="prediction", index=YTRVar)
    pred.to_csv("JSP.csv")
    """
    norm = ZTETT.rename(
        columns={'DAY': 'd_n', 'HOUR': 'h_n', 'ENERGIA1': 'e_n', 'TEMPERATURA': 't_n', 'UMIDADE': 'u_n', 'CHUVA': 'c_n',
                 'IRRADIACAO': 'i_n'})
    newTab = pandas.concat([norm, pred], axis=1)
    newTab.to_csv("testPred2.csv")
    allTab = pandas.concat([newTab, X], axis=1, join="inner")
    allTab.to_csv("testPredAll.csv")
    """

    """"
    X_train, X_test, y_train, y_test = train_test_split(X_normal, Y_normal)
    y_train = y_train.astype('float64')
    y_test = y_test.astype('float64')
    scaler = StandardScaler()
    scaler.fit(X_train)

    X_train = scaler.transform(X_train)
    X_test = scaler.transform(X_test)



    mlp = MLPClassifier(hidden_layer_sizes=(13, 13, 13), max_iter=1000)
    sortie = mlp.fit(X_train, y_train)
    print(sortie)
    predictions = mlp.predict(X_test)
    print(confusion_matrix(y_test,predictions))
    print(classification_report(y_test, predictions))

    """
    ###
    # model = MLPClassifier(solver='lbfgs', alpha=1e-5, hidden_layer_sizes=(5, 2), random_state=1)
    # model.fit(train[columns], train[target])

    # predictions = model.predict(test[columns])
    # square_error = mean_squared_error(predictions, test[target])
    # print(square_error)


if __name__ == '__main__':
    main()
