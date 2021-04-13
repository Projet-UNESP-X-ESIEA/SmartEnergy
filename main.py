
from Network import Network
from FCLayer import FCLayer
from ActivationLayer import ActivationLayer
from utilities import tanh, tanh_prime, mse, mse_prime
from keras.datasets import mnist
from keras.utils import np_utils
import pandas
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans
from sklearn.decomposition import PCA
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.ensemble import RandomForestRegressor
from sklearn.neural_network import MLPClassifier,MLPRegressor
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import classification_report,confusion_matrix,accuracy_score,f1_score


def cour1():
    (x_train, y_train), (x_test, y_test) = mnist.load_data()

    # training data : 60000 samples
    # reshape and normalize input data
    x_train = x_train.reshape(x_train.shape[0], 1, 28 * 28)
    x_train = x_train.astype('float32')
    x_train /= 255
    # encode output which is a number in range [0,9] into a vector of size 10
    # e.g. number 3 will become [0, 0, 0, 1, 0, 0, 0, 0, 0, 0]
    y_train = np_utils.to_categorical(y_train)

    # same for test data : 10000 samples
    x_test = x_test.reshape(x_test.shape[0], 1, 28 * 28)
    x_test = x_test.astype('float32')
    x_test /= 255
    y_test = np_utils.to_categorical(y_test)

    # Network
    net = Network()
    net.add(FCLayer(28 * 28, 100))  # input_shape=(1, 28*28)    ;   output_shape=(1, 100)
    net.add(ActivationLayer(tanh, tanh_prime))
    net.add(FCLayer(100, 50))  # input_shape=(1, 100)      ;   output_shape=(1, 50)
    net.add(ActivationLayer(tanh, tanh_prime))
    net.add(FCLayer(50, 10))  # input_shape=(1, 50)       ;   output_shape=(1, 10)
    net.add(ActivationLayer(tanh, tanh_prime))

    # train on 1000 samples
    # as we didn't implemented mini-batch GD, training will be pretty slow if we update at each iteration on 60000 samples...
    net.use(mse, mse_prime)
    net.fit(x_train[0:1000], y_train[0:1000], epochs=35, learning_rate=0.1)

    # test on 3 samples
    out = net.predict(x_test[0:3])
    print("\n")
    print("predicted values : ")
    print(out, end="\n")
    print("true values : ")
    print(y_test[0:3])


def analyseColumn(ptable, pnbX):
    rep = [0 for _ in range(pnbX)]
    print(rep)
    rep[0] = ptable.max()
    rep[1] = ptable.min()
    print(rep)
    return rep

def minmax_norm(df_input):
    return (df_input - df_input.min()) / (df_input.max() - df_input.min())

def eval_model(y_true,y_pred, x_nor):
    #matrice de confusion
    mc = confusion_matrix(y_true,y_pred)
    print("Confusion matrix :")
    print(mc)
    #taux d'erreur
    err = 1 - accuracy_score(y_true, y_pred)
    print("Err-rate = ", err)
    #F1-score
    f1 = f1_score(x_nor[3])
    print("F1-Score = ",f1)


def main():
    heures_data = pandas.read_table("ALLvalue.csv", sep=",", header=0, decimal=".")
    del heures_data["ENERGIA2"]
    print(heures_data.columns)
    print(heures_data.shape)
    """
    heures_data = heures_data[heures_data["ENERGIA1"] > 0]
    heures_data = heures_data.dropna(axis=0)
    plt.hist(heures_data["ENERGIA1"])
    plt.show()

    kmeans_model = KMeans(n_clusters=20, random_state=1)
    good_columns = heures_data._get_numeric_data()
    kmeans_model.fit(good_columns)
    labels = kmeans_model.labels_
    
    pca_2 = PCA(2)
    plot_columns = pca_2.fit_transform(good_columns)
    plt.scatter(x=plot_columns[:,0],y=plot_columns[:,1], c=labels)
    plt.show()
    print(heures_data.corr()["ENERGIA1"])

    columns = heures_data.columns.tolist()
    columns = [c for c in columns if c not in ["CHUVA", "ENERGIA1", "YEAR"]]
    target = "ENERGIA1"

    train = heures_data.sample(frac=0.8, random_state=1)
    test = heures_data.loc[~heures_data.index.isin(train.index)]
    print(train.shape)
    print(test.shape)
    """
    
    ###
    print(heures_data.head())
    print(heures_data.describe().transpose())

    X = heures_data.drop(["MONTH","DAY","YEAR","HOUR"], axis=1)
    X = heures_data[heures_data["ENERGIA1"] > 0]
    Y = heures_data["ENERGIA1"]

    X_normal = minmax_norm(X)
    Y_normal = [i for i in range(X_normal.shape[0])]

    print(X_normal.describe().transpose())
    X_train, X_test, y_train, y_test = train_test_split(X_normal, Y_normal, test_size=0.2)

    XTrain = X_train
    scaler = StandardScaler(with_mean=True, with_std=False)

    scaler.fit(XTrain)
    #ZTrain = scaler.transform(XTrain)
    ZTrain = X_train
    print(ZTrain.mean(axis=1))
    #ZTest = scaler.transform(X_test)
    ZTest = X_test
    ZTT = X_train
    print(len(ZTT))
    print(ZTT["ENERGIA1"])

    pmc_sklearn = MLPRegressor(hidden_layer_sizes=(13, 13, 13), random_state=1, max_iter=1500, solver="lbfgs")
    pmc_sklearn.fit(ZTrain.drop(["ENERGIA1"], axis=1), ZTT["ENERGIA1"])
    predm_sklearn = pmc_sklearn.predict(ZTest.drop(["ENERGIA1"], axis=1))

    ZTETT = ZTest
    #print(predm_sklearn)
    #print(pmc_sklearn.score(ZTest, ZTETT[3]))
    #eval_model(ZTETT[3], predm_sklearn, X_test.T)

    plt.figure()
    plt.rcParams["figure.figsize"] = (1, 100)
    plt.gca().xaxis.set_ticks(range(0, 10000, 10), minor=True)
    plt.plot([e for e in range(len(predm_sklearn))],predm_sklearn, "x", label="prevision")
    plt.plot([e for e in range(len(ZTETT["ENERGIA1"]))], ZTETT["ENERGIA1"], "x", label="original")
    plt.legend()

    plt.savefig('test.png', dpi=300)

    pred = pandas.Series(predm_sklearn, name="prediction", index=y_test)
    norm = ZTETT.rename(columns={'DAY': 'd_n', 'HOUR': 'h_n', 'ENERGIA1': 'e_n', 'TEMPERATURA': 't_n', 'UMIDADE': 'u_n', 'CHUVA': 'c_n', 'IRRADIACAO': 'i_n'})
    newTab = pandas.concat([norm, pred], axis=1)
    newTab.to_csv("testPred2.csv")
    allTab = pandas.concat([newTab,X], axis=1, join="inner")
    allTab.to_csv("testPredAll.csv")

    
    

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
    #model = MLPClassifier(solver='lbfgs', alpha=1e-5, hidden_layer_sizes=(5, 2), random_state=1)
    #model.fit(train[columns], train[target])

    #predictions = model.predict(test[columns])
    #square_error = mean_squared_error(predictions, test[target])
    #print(square_error)







if __name__ == '__main__':
    main()
